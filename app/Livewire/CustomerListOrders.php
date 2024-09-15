<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Review;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class CustomerListOrders extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        $orderQuery = Order::query()->whereHas('request', function ($query) {
            $query->where('customer_id', Auth::id());
        });

        // Log::info('Order Query:', ['orderQuery' => $orderQuery]);

        return $table
            ->query($orderQuery)
            ->columns([
                Stack::make([
                    Stack::make([
                        TextColumn::make('request.product.name')
                            ->label('Product Name')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('order_date')
                            ->label('Order Date')
                            ->sortable()
                            ->formatStateUsing(function ($state) {
                                return 'Order Date: ' . Carbon::parse($state)->format('Y-m-d');
                            }),
                        TextColumn::make('request.total_amount')
                            ->label('Total Amount')
                            ->money('MYR')
                            ->sortable()
                            ->formatStateUsing(function ($state) {
                                return 'Total Amount: ' . $state;
                            }),
                        TextColumn::make('shipping_fees')
                            ->label('Shipping Fees')
                            ->money('MYR')
                            ->sortable()
                            ->formatStateUsing(function ($state) {
                                return 'Shipping Fees: ' . $state;
                            }),
                        TextColumn::make('request.quantity')
                            ->label('Stock')
                            ->sortable()
                            ->formatStateUsing(function ($state) {
                                return $state . ' units';
                            }),
                        TextColumn::make('status')
                            ->label('Order Status')
                            ->badge()
                            ->sortable()
                            ->colors([
                                'info' => config('staticdata.order.order_status.pending'),
                                'warning' => config('staticdata.order.order_status.in_delivery'),
                                'success' => config('staticdata.order.order_status.shipped'),
                            ])
                            ->icon(fn($state) => match ($state) {
                                config('staticdata.order.order_status.pending') => config('staticdata.icons.pending'),
                                config('staticdata.order.order_status.in_delivery') => config('staticdata.icons.truck'),
                                config('staticdata.order.order_status.shipped') => config('staticdata.icons.check_circle'),
                            }),
                    ]),
                ])->space(3),
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
                    ->label('View Order Details')
                    ->icon('heroicon-s-eye')
                    ->color('info')
                    ->url(fn(Order $order): string => route('order-view', ['order' => $order])),
                Action::make('leave_product_review')
                    ->label('Leave Product Review')
                    ->icon('heroicon-c-pencil-square')
                    ->color(fn(Order $order) => $this->hasReview($order) ? 'opacity-50' : 'success')
                    ->url(fn(Order $order): string => route('leave-product-review', ['order' => $order]))
                    ->visible(fn(Order $order) => $order->status === config('staticdata.order.order_status.shipped'))
                    ->disabled(fn(Order $order) => $this->hasReview($order))
                    ->extraAttributes(fn(Order $order) => $this->hasReview($order) ? ['class' => 'grayed-out'] : []),
            ])
            ->defaultPaginationPageOption(9)
            ->paginated([
                9,
                18,
                27,
                'all',
            ]);
    }

    private function hasReview(Order $order): bool
    {
        return Review::where('customer_id', Auth::id())
            ->where('product_id', $order->request->product_id)
            ->exists();
    }

    public function render()
    {
        return view('livewire.customer-list-orders')->layout('layouts.app');
    }
}
