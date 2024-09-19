<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use layout;

class CustomerProductGrid extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->whereNull('deleted_at')->with('reviews'))
            ->columns([
                Stack::make([
                    Stack::make([
                        TextColumn::make('name')
                            ->label('Product Name')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('category.name')
                            ->label('Product Category')
                            ->sortable()
                            ->formatStateUsing(function ($state) {
                                return 'Category: ' . $state;
                            }),
                        TextColumn::make('price')
                            ->money('MYR')
                            ->sortable(),
                        TextColumn::make('stock')
                            ->label('Stock')
                            ->sortable()
                            ->formatStateUsing(function ($state) {
                                return $state . ' units available';
                            }),
                        TextColumn::make('reviews.average_rating')
                            ->label('Average Rating')
                            ->getStateUsing(fn($record) => $record->averageRating())
                            ->formatStateUsing(function ($state) {
                                if ($state === 0) {
                                    return 'No reviews yet';
                                } else {
                                    return number_format($state, 1, '.', '') . ' out of 5';
                                }
                            })
                            ->sortable()
                            ->badge()
                            ->colors([
                                'danger' => fn($state) => $state < 2,
                                'warning' => fn($state) => $state >= 2 && $state < 4,
                                'success' => fn($state) => $state >= 4,
                            ])
                            ->icon(config('staticdata.icons.star')),
                    ]),
                ])->space(4),
                Panel::make([
                    Split::make([
                        ColorColumn::make('color')
                            ->grow(false),
                        TextColumn::make('description')
                            ->color('gray'),
                    ]),
                ]),
            ])
            ->filters([
                //
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->actions([
                ViewAction::make()
                    ->label('Make Order Request')
                    ->icon('heroicon-s-plus')
                    ->color('success')
                    ->url(fn(Product $product): string => route('product-view', ['product' => $product])),
            ])
            ->defaultPaginationPageOption(9)
            ->paginated([
                9,
                18,
                27,
                'all',
            ]);
    }

    public function render()
    {
        return view('livewire.customer-product-grid')->layout('layouts.app');
    }
}
