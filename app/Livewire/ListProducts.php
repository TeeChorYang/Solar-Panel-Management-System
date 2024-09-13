<?php
 
namespace App\Livewire;
 
use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\ForceDeleteBulkAction;
 
class ListProducts extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->where("supplier_id", auth()->user()->id))
            ->columns([
              TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
              TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
              TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
              TextColumn::make('price')
                    ->label('Price')
                    ->money('MYR')
                    ->sortable(),
              TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()
                ->model(model: Product::class)
                ->form([
                    TextInput::make('supplier_id')
                    ->label('Supplier ID')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->default(auth()->user()->id),
                Select::make('category_id')
                    ->label('Category')
                    ->required()
                    ->options(
                        Category::all()->pluck('name', 'id')
                    )
                    ->searchable(),
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->prefix('RM'),
                TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->numeric(),
                ])
            ])
            ->actions([
               EditAction::make()
               ->form([
                TextInput::make('supplier_id')
                ->label('Supplier')
                ->required()
                ->disabled()
                ->hidden()
                ->dehydrated(),
            Select::make('category_id')
                ->label('Category')
                ->required()
                ->options(
                    Category::all()->pluck('name', 'id')
                )
                ->searchable(),
            TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),
            Textarea::make('description')
                ->label('Description')
                ->required()
                ->rows(3)
                ->columnSpanFull(),
            TextInput::make('price')
                ->label('Price')
                ->required()
                ->numeric()
                ->prefix('RM'),
            TextInput::make('stock')
                ->label('Stock')
                ->required()
                ->numeric(),
               ]),
               DeleteAction::make(),
               RestoreAction::make(),
            ])
            ->bulkActions([
               BulkActionGroup::make([
                   DeleteBulkAction::make(),
                   ForceDeleteBulkAction::make(),
                   RestoreBulkAction::make(),
                ]),
            ]);
    }
    
    public function render(): View
    {
        return view('livewire.list-products')
        ->layout('layouts.app');
    }
}