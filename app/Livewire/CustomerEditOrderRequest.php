<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\OrderRequest;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class CustomerEditOrderRequest extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    public OrderRequest $orderRequest;
    public $product;
    public $quantity;
    public $supplier;
    public $shipping_address;

    public function mount(OrderRequest $orderRequest)
    {
        $this->orderRequest = $orderRequest;
        $this->product = Product::find(intval($this->orderRequest->product_id));
        $this->supplier = User::find(intval($this->product->supplier_id));
        $this->form->fill([
            'quantity' => $this->orderRequest->quantity,
            'shipping_address' => $this->orderRequest->shipping_address,
        ]);
    }

    protected function getFormModel(): OrderRequest
    {
        return $this->orderRequest;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('quantity')
                    ->required()
                    ->label('Quantity')
                    ->numeric()
                    ->rules(['required', 'integer', 'min:1']),
                Textarea::make('shipping_address')
                    ->required()
                    ->label('Shipping Address')
                    ->rules(['required', 'string', 'max:255', 'regex:' . config('staticdata.regex_malaysian_address')]),
            ]);
    }

    public function save()
    {
        $validatedData = $this->validate([
            'quantity' => ['required', 'integer', 'min:1', function ($attribute, $value, $fail) {
                if ($value > intval($this->product->stock)) {
                    $fail('The quantity entered exceeds the available stock.');
                }
            }],
            'shipping_address' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) {
                if (!preg_match(config('staticdata.regex_malaysian_address'), $value)) {
                    $fail('The shipping address is invalid! Please enter a valid Malaysian address.');
                }
            }],
        ]);

        $this->orderRequest->fill($validatedData);
        $this->orderRequest->save();

        session()->flash('message', 'Order request quantity changed successfully.');

        return redirect()->route('customer-list-order-requests');
    }

    public function displayOrderRequestInfoList(Infolist $infolist): Infolist
    {
        $assocSupplier = User::find(intval($this->product->supplier_id));

        return $infolist
            ->record($this->orderRequest, $this->supplier, $this->product)
            ->schema([
                // dot notation to obtain the supplier's name
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
                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
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
                TextEntry::make('total_amount')
                    ->label('Total Amount')
                    ->money('MYR'),
                TextEntry::make('created_at')
                    ->label('Request Date')
                    ->date('Y-m-d'),
            ]);
    }

    public function render()
    {
        return view('livewire.customer-edit-order-request')->layout('layouts.app');
    }
}
