<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Filament\Admin\Resources\DocumentResource\DocumentResource;
use App\Models\File;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class DocumentRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

//    protected static ?string $badge = 'new';

    public function form(Form $form): Form
    {

        return DocumentResource::form($form,true);
    }




    public function table(Table $table): Table
    {

        return DocumentResource::table($table);
    }
}
