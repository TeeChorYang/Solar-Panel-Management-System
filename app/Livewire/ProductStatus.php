<?php

namespace App\Livewire;

use layout;
use App\Models\Product;
use Livewire\Component;
use Illuminate\View\View;
use Filament\Tables\Table;
use App\Models\OrderRequest;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;


class ProductStatus extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public function table(Table $table): Table
    {


        return $table
            ->query(
                OrderRequest::query()
                    ->join('products', 'order_requests.product_id', '=', 'products.id')
                    ->where('products.supplier_id', auth()->user()->id)
                    ->orderBy('order_requests.id', 'desc')
            )
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('MYR')
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->label('Approved At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace('_', ' ', $state))),
            ])
            ->actions([
                Action::make('status')
                    ->label('Update Status')
                    ->icon('heroicon-m-check-badge')
                    ->color('warning')
                    ->fillForm(fn(OrderRequest $record): array => [
                        'status' => $record->status,
                    ])
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options(config('staticdata.order.order_status'))
                            ->searchable(),
                    ])
                    ->action(function (array $data, OrderRequest $record): void {
                        $record->status = $data['status'];
                        $record->save();
                    }),
            ]);
    }

    public function render(): View
    {
        return view('livewire.product-status')
            ->layout('layouts.app');
    }
}
