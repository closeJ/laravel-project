<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'admin/manage',
        'login',
        'other/valian',
        'distributor/save',
        'distributor/update_credit',
        'proxy/save',
        'proxy/update_credit',
        'platform/save',
        'platform/update_credit',
        'player/save',
        'gameControl/save',
        'gameControl/del',
        'gameControl/saveBatch',
        'gameControl/saveNowSet',
        'gameControl/setListValue',
        'gameControl/delSetListValue',
        'gameControl/tempSave',
        'gameControl/saveOnLine',
    ];
}
