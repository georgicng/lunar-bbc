<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface ProductCustomisation
{
    /**
     * The related product.
     */
    public function product(): BelongsTo;

}
