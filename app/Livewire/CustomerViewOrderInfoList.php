<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Tables\Columns\BadgeColumn;

class CustomerViewOrderInfoList extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    public Order $order;
    public $orderRequest;
    public $product;
    public $supplier;

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->orderRequest = $this->order->request;
        $this->product = Product::find(intval($this->orderRequest->product_id));
        $this->supplier = User::find(intval($this->product->supplier_id));
    }

    public function displayProductInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            // order needs to be passed in so that request, product and supplier can be accessed
            // data structure is like a tree structure
            ->record($this->order, $this->orderRequest, $this->product, $this->supplier)
            ->schema([
                TextEntry::make('request.product.supplier.name')
                    ->label('Supplier Name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('request.product.supplier.email')
                    ->label('Supplier Email'),
                TextEntry::make('request.product.name')
                    ->label('Product Name'),
                TextEntry::make('request.product.category.name')
                    ->label('Product Category'),
                TextEntry::make('request.product.price')
                    ->label('Unit Price')
                    ->money('MYR'),
            ]);
    }

    public function displayOrderInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->order, $this->orderRequest)
            ->schema([
                TextEntry::make('id')
                    ->label('Order ID')
                    ->weight(FontWeight::Bold),
                TextEntry::make('order_date')
                    ->label('Order Date')
                    ->date('Y-m-d'),
                TextEntry::make('request.created_at')
                    ->label('Request Date')
                    ->date('Y-m-d'),
                TextEntry::make('request.approved_at')
                    ->label('Request Approved Date')
                    ->date('Y-m-d'),
                TextEntry::make('status')
                    ->label('Order Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->colors([
                        'info' => 'pending',
                        'warning' => 'in_delivery',
                        'success' => 'shipped',
                    ])
                    ->icon(fn($state) => match ($state) {
                        'pending' => config('staticdata.icons.pending'),
                        'in_delivery' => config('staticdata.icons.truck'),
                        'shipped' => config('staticdata.icons.check_circle'),
                    }),
                TextEntry::make('request.quantity')
                    ->label('Quantity Ordered')
                    ->formatStateUsing(function ($state) {
                        return $state . ' units';
                    }),
                TextEntry::make('request.shipping_address')
                    ->label('Shipping Address'),
            ]);
    }

    public function displayAmountInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->order, $this->orderRequest)
            ->schema([
                TextEntry::make('request.total_amount')
                    ->label('Amount')
                    ->money('MYR'),
                TextEntry::make('shipping_fees')
                    ->label('Shipping Fees')
                    ->money('MYR'),
                TextEntry::make('request.total_amount')
                    ->label('Total Amount')
                    ->money('MYR')
                    ->formatStateUsing(function () {
                        $totalAmount = $this->orderRequest->total_amount + $this->order->shipping_fees;
                        return 'MYR ' . number_format($totalAmount, 2);
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.customer-view-order-info-list')->layout('layouts.app');
    }
}
