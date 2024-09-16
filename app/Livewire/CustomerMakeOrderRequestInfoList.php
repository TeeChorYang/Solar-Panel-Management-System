<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\OrderRequest;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class CustomerMakeOrderRequestInfoList extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    public $product;
    public $quantity;
    public $shipping_address;

    public function mount()
    {
        $this->product = Product::find($this->product);
    }

    public function makeOrderRequestInfoList(Infolist $infolist): Infolist
    {
        // Get the selected product and its associated supplier
        $assocSupplier = User::find(intval($this->product->supplier_id));

        return $infolist
            // puts both the product and the associated supplier into the record
            ->record($this->product, $assocSupplier)
            ->schema([
                // dot notation to obtain the supplier's name
                TextEntry::make('supplier.name')
                    ->label('Supplier Name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('supplier.email')
                    ->label('Supplier Email'),
                TextEntry::make('name')
                    ->label('Product Name'),
                TextEntry::make('price')
                    ->label('Unit Price')
                    ->money('MYR'),
                TextEntry::make('stock')
                    ->label('Stock (Units Available)'),
                TextEntry::make('description')
                    ->label('Description'),
            ]);
    }

    public static function form(Form $form): Form
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
                    ->rules(['required', 'string', 'min:20', 'max:255', 'regex:' . config('staticdata.regex_malaysian_address')]),
            ]);
    }

    public function create()
    {
        $validatedData = $this->validate([
            'quantity' => ['required', 'integer', 'min:1', function ($attribute, $value, $fail) {
                if ($value > intval($this->product->stock)) {
                    $fail('The quantity entered exceeds the available stock!');
                }
            }],
            'shipping_address' => ['required', 'string', 'min:20', 'max:255', function ($attribute, $value, $fail) {
                if (!preg_match(config('staticdata.regex_malaysian_address'), $value)) {
                    $fail('The shipping address is invalid! Please enter a valid Malaysian address.');
                }
            }],
        ]);

        // Create an order request
        OrderRequest::create([
            'customer_id' => Auth::id(),
            'product_id' => $this->product->id,
            'quantity' => $validatedData['quantity'],
            'total_amount' => $this->product->price * $validatedData['quantity'],
            'status' => 'pending',
            'shipping_address' => $validatedData['shipping_address'],
        ]);

        // Optionally, update the product stock (for supplier)
        // $this->product->decrement('stock', $validatedData['quantity']);

        session()->flash('message', 'Order request placed successfully. Pending supplier approval');

        return redirect()->route('customer-product-grid');
    }

    public function render()
    {
        return view('livewire.customer-make-order-request-info-list')->layout('layouts.app');
    }
}
