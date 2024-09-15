<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Order;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;

class ManagerOrders extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_fees')
                    ->label('Shipping Fees')
                    ->money('MYR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->label('Order Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ViewAction::make()
                    ->form([
                        TextInput::make('request_id')
                            ->label('Request ID')
                            ->required()
                            ->disabled(),
                        DateTimePicker::make('order_date')
                            ->label('Order Date')
                            ->default(now())
                            ->disabled()
                            ->required(),
                        TextInput::make('shipping_fees')
                            ->label('Shipping Fees')
                            ->prefix('RM')
                            ->required()
                            ->numeric(),
                        TextInput::make('status')
                            ->label('Status')
                            ->required()
                            ->disabled()

                    ]),
            ]);
    }


    public function render()
    {
        return view('livewire.manager-orders')
            ->layout('layouts.app');
    }
}
