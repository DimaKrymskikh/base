<?php

namespace Base\Jwt;

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token as TokenInterface;


/**
 * Создаёт и проверяет токены
 * @property Builder $tokenBuilder
 * @property Sha256 $algorithm Используемый алгоритм при создании токена
 * @property InMemory $signingKey Особый формат хранения секретного ключа
 * @property string $secretKey Секретный ключ, должен быть в формате MIME base64
 * @property string $iss URI стороны, генерирующей токен
 * @property string $aud URI стороны, принимающей токен
 * @property ?string $jti Строка, определяющая уникальный идентификатор данного токена (по-моему, без $jti можно обойтись)
 * @property int $uid Индентификатор пользователя из базы данных
 * @property \DateTimeImmutable $iat Время, определяющее момент, когда токен был создан
 * @property ?\DateTimeImmutable $nbf Время, определяющее момент, когда токен станет валидным
 * @property ?\DateTimeImmutable $exp Время, определяющее момент, когда токен станет невалидным
 */
class JwtHelper 
{
    private Builder $tokenBuilder;
    private Sha256 $algorithm;
    private InMemory $signingKey;

    private function __construct(
            string $secretKey,
            private string $iss,
            private string $aud, 
            private ?string $jti, 
            private int $uid, 
            private \DateTimeImmutable $iat, 
            private ?\DateTimeImmutable $nbf = null,
            private ?\DateTimeImmutable $exp = null
    )
    {
        $this->tokenBuilder = new Builder(new JoseEncoder(), ChainedFormatter::default());
        $this->algorithm = new Sha256();
        $this->signingKey = InMemory::base64Encoded($secretKey);
    }
    
    /**
     * Генерация токена
     * @param string $secretKey
     * @param string $iss
     * @param string $aud
     * @param string|null $jti
     * @param int $uid
     * @param string|null $nbfStep
     * @param string $expStep
     * @return TokenInterface
     */
    public static function generateToken(string $secretKey, string $iss, string $aud, ?string $jti, int $uid, ?string $nbfStep = null, ?string $expStep = null): TokenInterface
    {
        // Нет смысла указывать часовой пояс, потому что Lcobucci\JWT за ним не следит.
        // $token->claims()->get('iat') возвращает дату с дефолтным часовым поясом.
        $iat = new \DateTimeImmutable();
        $nbf = $nbfStep ? $iat->modify($nbfStep) : null;
        $exp = $expStep ? $iat->modify($expStep) : null;
        
        return (new static($secretKey, $iss, $aud, $jti, $uid, $iat, $nbf, $exp))->getToken();
    }
    
    /**
     * Извлечение токена из полученной стоки
     * @param string $strToken
     * @return TokenInterface
     */
    public static function getResultToken(string $strToken): TokenInterface
    {
        return (new Parser(new JoseEncoder()))->parse($strToken);
    }
    
    /**
     * Проверка действительности токена
     * @param TokenInterface $resultToken
     * @param string $secretKey
     * @return bool
     */
    public static function isValidToken(TokenInterface $resultToken, string $secretKey): bool
    {
        // По параметрам полученного токена и секретному ключу создаём оригинальный токен
        $originalToken = (new static(
                $secretKey,
                $resultToken->claims()->get('iss'),
                $resultToken->claims()->get('aud')[0],
                $resultToken->claims()->get('jti'),
                $resultToken->claims()->get('uid'),
                $resultToken->claims()->get('iat'),
                $resultToken->claims()->get('nbf'),
                $resultToken->claims()->get('exp')
        ))->getToken();
        
        // У полученного токена и оригинального токена должны совпадать подписи
        return $resultToken->signature()->toString() === $originalToken->signature()->toString();
    }
    
    /**
     * Создание токена
     * @return TokenInterface
     */
    private function getToken(): TokenInterface
    {
        $this->tokenBuilder = $this->tokenBuilder
            // Configures the issuer (iss claim)
            ->issuedBy($this->iss)
            // Configures the audience (aud claim)
            ->permittedFor($this->aud)
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($this->iat)
            // Configures a new claim, called "uid"
            ->withClaim('uid', $this->uid);
        
        // Configures the id (jti claim)
        if ($this->jti) {
            $this->tokenBuilder = $this->tokenBuilder->identifiedBy($this->jti);
        }
        
        // Configures the time that the token can be used (nbf claim)
        if ($this->nbf) {
            $this->tokenBuilder = $this->tokenBuilder->canOnlyBeUsedAfter($this->nbf);
        }
        
        // Configures the expiration time of the token (exp claim)
        if ($this->exp) {
            $this->tokenBuilder = $this->tokenBuilder->expiresAt($this->exp);
        }
        
        // Builds a new token
        return $this->tokenBuilder->getToken($this->algorithm, $this->signingKey);
    }
}
