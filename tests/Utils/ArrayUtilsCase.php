<?php

namespace Tests\Utils;

use PHPUnit\Framework\TestCase;

abstract class ArrayUtilsCase extends TestCase
{
    /**
     * Основная таблица:
     * CREATE TABLE quiz.quizzes (
     * 	id serial4 NOT NULL,
     * 	title text NOT NULL,
     * 	description text NULL,
     *  CONSTRAINT quizzes_pkey PRIMARY KEY (id),
     * );
     * 
     * Зависимая таблица:
     * CREATE TABLE quiz.quiz_items (
     *  id bigserial NOT NULL,
     *  status text NOT NULL,
     *  info text NOT NULL,
     *  quiz_id int4 NOT NULL,
     *  CONSTRAINT quiz_items_pkey PRIMARY KEY (id),
     *  CONSTRAINT quiz_items_quiz_id_fkey FOREIGN KEY (quiz_id) REFERENCES quiz.quizzes(id)
     * );
     * 
     * Структура массива - это данные из базы.
     * ref_id - это id зависимой таблицы.
     * 
     * @return array
     */
    protected function getFlatAndComplexArrays(): array
    {
        $flat = [
                (object) [
                    'id' => 177,
                    'title' => 'aaa',
                    'description' => 'qqqqq',
                    'ref_id' => 10,
                    'status' => 's',
                    'info' => 'text'
                ],
                (object) [
                    'id' => 215,
                    'title' => 'bbb',
                    'description' => 'zzzzz',
                    'ref_id' => 20,
                    'status' => 's',
                    'info' => 'string'
                ],
                (object) [
                    'id' => 215,
                    'title' => 'bbb',
                    'description' => 'zzzzz',
                    'ref_id' => 30,
                    'status' => 't',
                    'info' => 'characters'
                ]
            ];
        
        $complex = [
                177 => (object) [
                    'id' => 177,
                    'title' => 'aaa',
                    'description' => 'qqqqq',
                    'refs' => [
                        (object) [
                            'id' => 10,
                            'status' => 's',
                            'info' => 'text'
                        ]
                    ]
                ],
                215 => (object) [
                    'id' => 215,
                    'title' => 'bbb',
                    'description' => 'zzzzz',
                    'refs' => [
                        (object) [
                            'id' => 20,
                            'status' => 's',
                            'info' => 'string'
                        ],
                        (object) [
                            'id' => 30,
                            'status' => 't',
                            'info' => 'characters'
                        ]
                    ]
                ],
            ];
        
        return [$flat, $complex];
    }
    
    protected function getArraysForMovingIdToArrayKey(): array
    {
        $flat = [
                (object) [
                    'id' => 1,
                    'title' => 'aaa',
                    'description' => 'qqqqq',
                    'ref_id' => 10,
                ],
                (object) [
                    'id' => 2,
                    'title' => 'bbb',
                    'description' => 'zzzzz',
                    'ref_id' => 20,
                ],
                (object) [
                    'id' => 3,
                    'title' => 'cc',
                    'description' => 'xxx',
                    'ref_id' => 10,
                ]
            ];
        
        $complex = [
            10 => [
                (object) [
                    'id' => 1,
                    'title' => 'aaa',
                    'description' => 'qqqqq',
                    'ref_id' => 10,
                ],
                (object) [
                    'id' => 3,
                    'title' => 'cc',
                    'description' => 'xxx',
                    'ref_id' => 10,
                ]
            ],
            20 => [
                (object) [
                    'id' => 2,
                    'title' => 'bbb',
                    'description' => 'zzzzz',
                    'ref_id' => 20,
                ],
            ],
        ];
        
        return [$flat, $complex];
    }
    
    protected function getArraysForJoinTwoArraysById(string $field): array
    {
        [$flat, $items] = $this->getArraysForMovingIdToArrayKey();
        
        $contents = [
                (object) [
                    'id' => 10,
                    'name' => 'abcde',
                    'status' => 'bar',
                ],
                (object) [
                    'id' => 20,
                    'name' => 'rlmn',
                    'status' => 'foo',
                ],
                (object) [
                    'id' => 30,
                    'name' => 'oprs',
                    'status' => 'bar',
                ],
            ];
        
        $results = [
                (object) [
                    'id' => 10,
                    'name' => 'abcde',
                    'status' => 'bar',
                    $field => $items[10]
                ],
                (object) [
                    'id' => 20,
                    'name' => 'rlmn',
                    'status' => 'foo',
                    $field => $items[20]
                ],
                (object) [
                    'id' => 30,
                    'name' => 'oprs',
                    'status' => 'bar',
                    $field => []
                ],
            ];
        
        return [$results, $contents, $items];
    }
}
