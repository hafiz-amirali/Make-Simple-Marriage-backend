<?php

namespace App\Models\Enums;

use App\Models\Enums\Enum;


// Encapsulating enumerated constants 
class FormFieldType extends Enum
{
    const text = 0;
    const checkbox = 1;
    const datetime = 2;
    const email = 3;
    const numeric = 4;
    const textarea = 5;
    const dropdown = 6;
}
