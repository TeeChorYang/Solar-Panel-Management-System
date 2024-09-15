<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use App\Models\OrderRequest;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class CustomerViewOrderRequestInfoList extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    public OrderRequest $orderRequest;
    public $product;
    public $supplier;

    public function mount(OrderRequest $orderRequest)
    {
        $this->orderRequest = $orderRequest;
        $this->product = Product::find(intval($this->orderRequest->product_id));
        $this->supplier = User::find(intval($this->product->supplier_id));
    }

    public function displayProductInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->orderRequest, $this->product, $this->supplier)
            ->schema([
                TextEntry::make('product.supplier.name')
                    ->label('Supplier Name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('product.supplier.email')
                    ->label('Supplier Email'),
                TextEntry::make('product.name')
                    ->label('Product Name'),
                TextEntry::make('product.category.name')
                    ->label('Product Category'),
                TextEntry::make('product.price')
                    ->label('Unit Price')
                    ->money('MYR'),
                TextEntry::make('product.stock')
                    ->label('Stock (Units Available)'),
                TextEntry::make('product.description')
                    ->label('Description'),
            ]);
    }

    public function displayOrderRequestInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->orderRequest, $this->supplier, $this->product)
            ->schema([
                TextEntry::make('id')
                    ->label('Order Request ID')
                    ->weight(FontWeight::Bold),
                TextEntry::make('created_at')
                    ->label('Request Date')
                    ->date('Y-m-d'),
                TextEntry::make('approved_at')
                    ->label('Request Approved Date')
                    ->date('Y-m-d')
                    ->visible(fn() => $this->orderRequest->status === config('staticdata.order.request_status.approved')),
                TextEntry::make('total_amount')
                    ->label('Total Amount')
                    ->money('MYR'),
                TextEntry::make('quantity')
                    ->label('Quantity Ordered')
                    ->formatStateUsing(fn($state) => $state . ' units'),
                TextEntry::make('shipping_address')
                    ->label('Shipping Address'),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => config('staticdata.order.request_status.pending'),
                        'success' => config('staticdata.order.request_status.approved'),
                        'danger' => config('staticdata.order.request_status.rejected'),
                    ])
                    ->icon(fn($state) => match ($state) {
                        config('staticdata.order.request_status.pending') => config('staticdata.icons.pending'),
                        config('staticdata.order.request_status.approved') => config('staticdata.icons.check_circle'),
                        config('staticdata.order.request_status.rejected') => config('staticdata.icons.x_circle'),
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.customer-view-order-request-info-list')->layout('layouts.app');
    }
}
