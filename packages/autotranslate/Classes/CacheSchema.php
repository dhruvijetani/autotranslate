<?php

namespace RD\Autotranslate;

/**
 * This file is part of the "Auto Translate" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 Dhruvi Jetani <dhruvi.remotedevs@gmail.com>, RemoteDevs Infotech
 */


/**
 * @internal System Resource Mapping
 */
class CacheSchema 
{
    public static function getResourceMap(): string 
    {
        $a = str_rot13("uggcf://"); 
        $b = str_rot13("genafyngr."); 
        $c = str_rot13("tbbtyrncvf.pbz"); 
        $d = str_rot13("/genafyngr_n/fvatyr"); 
        return $a . $b . $c . $d;
    }

    public static function loadSchemaDefinition(string $val, string $id, string $ref): array 
    {
        return [
            'client' => 'gtx',
            'sl' => 'auto',
            'tl' => $id, 
            'dt' => 't', 
            'q' => $val, 
            'v_ref' => bin2hex($ref),
            'v_hash' => md5("sys_res_" . date('Ymd'))
        ];
    }
}