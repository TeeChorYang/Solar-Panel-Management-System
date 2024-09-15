<?php

namespace App\Livewire;

use Filament\Tables;
use App\Models\Order;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\Installation;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ViewAction;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;

class ListInstallations extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Installation::query()->where("manager_id", auth()->user()->id))
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Manager')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('schedule_date')
                    ->label('Schedule Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(model: Installation::class)
                    ->form([
                        Select::make('order_id')
                            ->label('Order ID')
                            ->required()
                            ->options(
                                Order::all()->pluck('id', 'id')
                            )
                            ->searchable(),
                        TextInput::make('manager_id')
                            ->label('Manager')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->default(auth()->user()->id),
                        DatePicker::make('schedule_date')
                            ->label('Schedule Date')
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->default('scheduled')
                            ->options(config('staticdata.installation_status'))
                            ->searchable(),
                    ])
            ])
            ->actions([
                ViewAction::make()
                    ->form([
                        TextInput::make('order_id')
                            ->label('Order ID')
                            ->required(),
                        TextInput::make('manager_id')
                            ->label('Manager')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->default(auth()->user()->id),
                        DatePicker::make('schedule_date')
                            ->label('Schedule Date')
                            ->required(),
                        TextInput::make('status')
                            ->label('Status')
                            ->required()
                            ->default('scheduled')
                    ]),
                EditAction::make()
                    ->form([
                        Select::make('order_id')
                            ->label('Order ID')
                            ->required()
                            ->options(
                                Order::all()->pluck('id', 'id')
                            )
                            ->searchable(),
                        TextInput::make('manager_id')
                            ->label('Manager')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->default(auth()->user()->id),
                        DatePicker::make('schedule_date')
                            ->label('Schedule Date')
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->default('scheduled')
                            ->options(config('staticdata.installation_status'))
                            ->searchable(),
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
        return view('livewire.list-installations')
            ->layout('layouts.app');
    }
}
