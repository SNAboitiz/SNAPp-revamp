<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard;
use Illuminate\Contracts\Support\Htmlable;

class DashboardPage extends Dashboard
{
    public function getTitle(): string|Htmlable
    {
        return '';
    }
}
