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
use Filament\Tables\Actions\DeleteAction;
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
use Carbon\Carbon;

class ManagerOrderStatus extends Component implements HasForms, HasTable
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
        $orders = Order::query()
            ->whereIn('request_id', $orderRequests->pluck('id'))
            ->get();

        return $table
            ->query(
                OrderRequest::query()
                    ->whereIn('id', $orders->pluck('request_id'))
                    ->whereNull('deleted_at')
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
                TextColumn::make('orders.order_date')
                    ->label('Order Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('orders.shipping_fees')
                    ->label('Shipping Fees')
                    ->money('MYR')
                    ->sortable(),
                TextColumn::make('orders.updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('orders.status')
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
                        'status' => Order::where('request_id', $record->id)->first()->status ?? '',
                    ])
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options(config('staticdata.order.order_status'))
                            ->searchable(),
                    ])
                    ->action(function (array $data, OrderRequest $record): void {
                        $order = Order::where('request_id', $record->id)->first();
                        if ($order) {
                            $order->status = $data['status'];
                            $order->save();

                            
                            $record->status = $data['status'];
                            $record->save();

                            Notification::make()
                                ->title('Status Updated Successfully')
                                ->success()
                                ->color('success')
                                ->send();
                        }
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

                        Notification::make()
                            ->title('Order Made Successfully')
                            ->success()
                            ->color('success')
                            ->send();
                    })->visible(fn(OrderRequest $record) => !Order::where('request_id', $record->id)->exists()),
                DeleteAction::make()
                    ->action(function (OrderRequest $record): void {
                        $order = Order::where('request_id', $record->id)->first();
                        if ($order) {
                            $order->delete();

                            $record->deleted_at = Carbon::now();
                            $record->save();

                            Notification::make()
                                ->title('Order Deleted Successfully')
                                ->success()
                                ->send();
                        }
                    }),
            ]);
    }

    public function render(): View
    {
        return view('livewire.manager-order-status')
            ->layout('layouts.app');
    }
}