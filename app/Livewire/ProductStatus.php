<?php

namespace App\Livewire;

use layout;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Illuminate\View\View;
use Filament\Tables\Table;
use App\Models\OrderRequest;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ProductStatus extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    /**
     * 
     *
     * @param int $supplierId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findOrderRequestsBySupplier(int $supplierId)
    {
        $orderRequests = OrderRequest::join('products', 'order_requests.product_id', '=', 'products.id')
            ->where('products.supplier_id', $supplierId)
            ->select('order_requests.*')
            ->get();

        return $orderRequests;
    }

    /**
     *
     *
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        $supplierId = Auth::user()->id;
        $orderRequests = $this->findOrderRequestsBySupplier($supplierId);
        return $table
            ->query(
                OrderRequest::query()
                    ->whereIn('id', $orderRequests->pluck('id'))
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
                // update status for order request is still required
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
                            ->options(config('staticdata.order.request_status'))
                            ->searchable(),
                    ])
                    ->action(function (array $data, OrderRequest $record): void {
                        $record->status = $data['status'];
                        // added logic to update approved_at field based on request status
                        if ($data['status'] === 'approved') {
                            $record->approved_at = now();
                        } else {
                            $record->approved_at = null;
                        }
                        $record->save();

                        Notification::make()
                            ->title('Status Updated Successfully')
                            ->success()
                            ->color('success')
                            ->send();
                    }),
                Action::make('makeOrder')
                    ->label('Place Order')
                    ->icon('heroicon-m-check-badge')
                    ->color('warning')
                    ->form([
                        DatePicker::make('order_date')
                            ->label('Order Date')
                            ->default(now())
                            ->required(),
                        TextInput::make('shipping_fees')
                            ->label('Shipping Fees')
                            ->prefix('RM')
                            ->default('0')
                            ->required()
                            ->numeric(),
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->default('pending')
                            ->options(config('staticdata.order.order_status'))
                            ->searchable(),
                    ])
                    ->action(function (array $data, OrderRequest $record): void {

                        $order = new Order();
                        $order->request_id = $record->id;
                        $order->order_date = $data['order_date'];
                        $order->shipping_fees = $data['shipping_fees'];
                        $order->status = $data['status'];
                        $order->save();

                        // request status and order status are two different things, do not save the request status with the order status

                        Notification::make()
                            ->title('Order Made and Status Updated Successfully')
                            ->success()
                            ->color('success')
                            ->send();
                    })->visible(fn(OrderRequest $record) => !Order::where('request_id', $record->id)->exists() && $record->status === 'approved'),
                // added logic so that place order is only visible when the order request is approved and its order has not been made
            ]);
    }

    /**
     * 
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.product-status')
            ->layout('layouts.app');
    }
}
