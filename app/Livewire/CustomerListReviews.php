<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class CustomerListReviews extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Review::query()->whereHas('product', function ($query) {
                $query->where('customer_id', Auth::id());
            }))
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Review Date')
                    ->date('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->color('warning')
                    ->url(fn(Review $review): string => route('edit-review', ['review' => $review])),
                DeleteAction::make()
                    ->label('Delete'),
            ])
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([
                10,
                15,
                20,
                'all'
            ]);
    }

    public function render()
    {
        return view('livewire.customer-list-reviews')->layout('layouts.app');
    }
}
