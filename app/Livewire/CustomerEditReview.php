<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class CustomerEditReview extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    public Review $review;
    public $product;
    public $supplier;
    public $rating;
    public $review_text;

    public function mount(Review $review)
    {
        $this->review = $review;
        $this->product = $this->review->product;
        $this->supplier = $this->product->supplier;
        $this->form->fill([
            'rating' => $this->review->rating,
            'review_text' => $this->review->review_text,
        ]);
    }

    public function displayProductInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            // data structure is like a tree structure
            ->record($this->review, $this->product, $this->supplier)
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

    public function save()
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

        $this->review->fill($validatedData);
        $this->review->save();

        session()->flash('message', 'Review updated successfully.');

        return redirect()->route('customer-list-reviews');
    }

    public function render()
    {
        return view('livewire.customer-edit-review')->layout('layouts.app');
    }
}
