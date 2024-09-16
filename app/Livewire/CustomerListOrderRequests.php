<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\OrderRequest;
use Livewire\WithPagination;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class CustomerListOrderRequests extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderRequest::query()->where('customer_id', Auth::id()))
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
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->money('MYR')
                    ->label('Total Amount')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->icon(fn($state) => match ($state) {
                        'pending' => config('staticdata.icons.pending'),
                        'approved' => config('staticdata.icons.check_circle'),
                        'rejected' => config('staticdata.icons.x_circle'),
                    }),
                TextColumn::make('created_at')
                    ->label('Request Date')
                    ->date('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->label('View')
                    ->url(fn(OrderRequest $orderRequest): string => route('view-order-request', ['orderRequest' => $orderRequest])),
                EditAction::make()
                    ->label('Edit')
                    ->color('warning')
                    ->visible(fn(OrderRequest $orderRequest) => $orderRequest->status !== 'approved')
                    ->url(fn(OrderRequest $orderRequest): string => route('edit-order-request', ['orderRequest' => $orderRequest])),
                DeleteAction::make()
                    ->label('Delete')
                    ->visible(fn(OrderRequest $orderRequest) => $orderRequest->status !== 'approved'),
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
        return view('livewire.customer-list-order-requests')->layout('layouts.app');
    }
}
