<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Installation;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class CustomerViewInstallationInfoList extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    public Installation $installation;
    public $order;
    public $orderRequest;
    public $product;
    public $supplier;

    public function mount(Installation $installation)
    {
        $this->installation = $installation;
        $this->order = $this->installation->order;
        $this->orderRequest = $this->order->request;
        $this->product = $this->order->request->product;
        $this->supplier = $this->product->supplier;
    }

    public function displayOrderInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->installation, $this->order, $this->orderRequest, $this->product, $this->supplier)
            ->schema([
                TextEntry::make('order.id')
                    ->label('Order ID')
                    ->weight(FontWeight::Bold),
                TextEntry::make('order.order_date')
                    ->label('Order Date')
                    ->date('Y-m-d'),
                TextEntry::make('order.request.product.supplier.name')
                    ->label('Supplier Name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('order.request.product.name')
                    ->label('Product Name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('order.request.quantity')
                    ->label('Quantity Ordered')
                    ->formatStateUsing(function ($state) {
                        return $state . ' units';
                    }),
                TextEntry::make('order.request.total_amount')
                    ->label('Total Amount')
                    ->money('MYR')
                    ->formatStateUsing(function () {
                        $totalAmount = $this->orderRequest->total_amount + $this->order->shipping_fees;
                        return 'MYR ' . number_format($totalAmount, 2);
                    }),
            ]);
    }

    public function displayInstallationInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->installation, $this->order, $this->orderRequest)
            ->schema([
                TextEntry::make('id')
                    ->label('Installation ID')
                    ->weight(FontWeight::Bold),
                TextEntry::make('manager.name')
                    ->label('Installation Manager Name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('manager.email')
                    ->label('Manager Email'),
                TextEntry::make('schedule_date')
                    ->label('Scheduled Date')
                    ->date('Y-m-d'),
                TextEntry::make('order.request.shipping_address')
                    ->label('Installation Address'),
                TextEntry::make('status')
                    ->label('Installation Status')
                    ->badge()
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
            ]);
    }

    public function render()
    {
        return view('livewire.customer-view-installation-info-list')->layout('layouts.app');
    }
}
