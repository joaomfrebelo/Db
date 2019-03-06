<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 JoÃ£o M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\Db;

/**
 * Description of EmptyResultException
 *
 * @author João Rebelo
 */
class EmptyResultException
    extends \Exception
{

    public function __construct(string $message = "", int $code = 0,
                                \Throwable $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }

}
