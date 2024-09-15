<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use Livewire\Component;
use Filament\Forms\Form;
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

class CustomerLeaveProductReview extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    public Order $order;
    public $product;
    public $supplier;
    public $orderRequest;
    public $rating;
    public $review_text;

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('rating')
                    ->required()
                    ->label('Rating')
                    ->numeric()
                    ->rules(['required', 'integer', 'min:1', 'max:5']),
                Textarea::make('review_text')
                    ->required()
                    ->label('Review')
                    ->rules(['required', 'string', 'min:30', 'max:255']),
            ]);
    }

    public function create()
    {
        $validatedData = $this->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5', function ($attribute, $value, $fail) {
                if ($value < 1 || $value > 5) {
                    $fail('Rating must be between 1 and 5.');
                }
            }],
            'review_text' => ['required', 'string', 'min:30', 'max:255', function ($attribute, $value, $fail) {
                if (strlen($value) < 30) {
                    $fail('Review must be at least 30 characters long.');
                }
            }],
        ]);

        Review::create([
            'customer_id' => Auth::id(),
            'product_id' => $this->product->id,
            'rating' => $validatedData['rating'],
            'review_text' => $validatedData['review_text'],
        ]);

        session()->flash('message', 'Review added successfully to product [' . $this->product->name . '].');

        return redirect()->route('customer-list-orders');
    }

    public function render()
    {
        return view('livewire.customer-leave-product-review')->layout('layouts.app');
    }
}
