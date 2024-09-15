<?php

namespace App\Livewire;

use layout;
use App\Models\Product;
use Livewire\Component;
use Illuminate\View\View;
use Filament\Tables\Table;
use App\Models\OrderRequest;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;


class ProductStatus extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public function table(Table $table): Table
    {
      
      
        return $table
       // ->query(
      //       Product::query()
      //           ->join('order_requests', 'order_requests.product_id', '=', 'products.id') // 关联 order_requests 表
      //           ->join('categories', 'products.category_id', '=', 'categories.id') // 关联 categories 表
      //           ->join('users', 'order_requests.customer_id', '=', 'users.id') // 关联 users 表获取 customer 名字
      //           ->where('products.supplier_id', auth()->user()->id) // 检查当前用户是否是供货商
      //           ->select(
      //               'products.*', 
      //               'order_requests.quantity', 
      //               'order_requests.total_amount', 
      //               'order_requests.status', 
      //               'order_requests.approved_at',
      //               'users.name as customer_name',  // 获取用户名字作为 customer_name
      //               'products.name as product_name' // 获取产品名字作为 product_name
      //           )
      //   )
      ->query(
            OrderRequest::query()
                ->join('products', 'order_requests.product_id', '=', 'products.id')
                ->where('products.supplier_id', auth()->user()->id)
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
              
                
                  SelectColumn::make('status')
                    ->label('Status')
                   
                   
                    ->options(config('staticdata.order.order_status'))
                    ->searchable(),

                
            ]);

    }

    public function render(): View
    {
        return view('livewire.product-status')
            ->layout('layouts.app');
    }
}
