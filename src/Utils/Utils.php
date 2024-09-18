<?php

namespace App\Utils;

class Utils
{
    /**
     * @param $shift
     * @return \DateTime
     * @throws \Exception
     */
    public static function getCurrentGMDate($shift = 0): \DateTime
    {
        return new \DateTime(
            date(
                'Y-m-d H:i:s',
                strtotime(
                    '+' . $shift . ' seconds',
                    strtotime(
                        gmdate(
                            'Y-m-d H:i:s'
                        )
                    )
                )
            )
        );
    }
}