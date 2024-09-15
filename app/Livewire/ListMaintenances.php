<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Installation;
use App\Models\MaintenanceLog;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;

class ListMaintenances extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(MaintenanceLog::query()->where("manager_id", auth()->user()->id))
            ->columns([
                Tables\Columns\TextColumn::make('installation_id')
                    ->label('Installation ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Manager')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->searchable(),
                Tables\Columns\TextColumn::make('log_date')
                    ->label('Log Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(model: Installation::class)
                    ->form([
                        Select::make('installation_id')
                            ->label('Installation ID')
                            ->required()
                            ->options(
                                Installation::all()->pluck('id', 'id')
                            )
                            ->searchable(),
                        TextInput::make('manager_id')
                            ->label('Manager')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->default(auth()->user()->id),
                        DateTimePicker::make('log_date')
                            ->label('Log Date')
                            ->required()
                            ->default(now()),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->default('scheduled')
                            ->options(config('staticdata.maintanance_status'))
                            ->searchable(),
                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
            ])
            ->actions([
                ViewAction::make()
                    ->form([
                        TextInput::make('installation_id')
                            ->label('Installation ID')
                            ->required()
                            ->disabled(),
                        TextInput::make('manager_id')
                            ->label('Manager')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->default(auth()->user()->id),
                        DateTimePicker::make('log_date')
                            ->label('Log Date')
                            ->required()
                            ->default(now()),
                        TextInput::make('status')
                            ->label('Status')
                            ->required()
                            ->disabled()
                            ->default('scheduled'),
                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->disabled()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                EditAction::make()
                    ->form([
                        Select::make('installation_id')
                            ->label('Installation ID')
                            ->required()
                            ->options(
                                Installation::all()->pluck('id', 'id')
                            )
                            ->searchable(),
                        TextInput::make('manager_id')
                            ->label('Manager')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->default(auth()->user()->id),
                        DateTimePicker::make('log_date')
                            ->label('Log Date')
                            ->required()
                            ->default(now()),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->default('scheduled')
                            ->options(config('staticdata.maintanance_status'))
                            ->searchable(),
                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.list-maintenances')
            ->layout('layouts.app');
    }
}
