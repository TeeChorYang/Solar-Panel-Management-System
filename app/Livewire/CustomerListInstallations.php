<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\Installation;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class CustomerListInstallations extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Installation::query()->whereHas('order', function ($query) {
                $query->whereHas('request', function ($subquery) {
                    $subquery->where('customer_id', Auth::id());
                });
            }))
            ->columns([
                TextColumn::make('id')
                    ->label('Installation ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order.id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order.request.product.name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('manager.name')
                    ->label('Manager Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('schedule_date')
                    ->label('Scheduled Date')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Installation Status')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->colors([
                        'warning' => 'scheduled',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->icon(fn($state) => match ($state) {
                        'scheduled' => config('staticdata.icons.pending'),
                        'completed' => config('staticdata.icons.check_circle'),
                        'cancelled' => config('staticdata.icons.x_circle'),
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->label('View')
                    ->url(fn(Installation $installation): string => route('installation-view', ['installation' => $installation])),
            ])
            ->defaultPaginationPageOption(10)
            ->paginated([
                10,
                15,
                20,
                'all',
            ]);
    }

    public function render()
    {
        return view('livewire.customer-list-installations')->layout('layouts.app');
    }
}
