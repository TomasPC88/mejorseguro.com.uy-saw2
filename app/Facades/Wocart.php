<?php

namespace App\Facades;

use Illuminate\Support\Facades\Session;

class Wocart
{
    protected $cart;

    public function __construct()
    {
        if (Session::has('wocart')) {
            $this->cart = Session::get('wocart');
        } else
            $this->clear();
    }

    public function clear(){
        $this->cart = (object)[
            'items' => collect([]),
            'totales' => collect([
                '858' => 0, //$U
                '840' => 0  //USD
            ]),
            'qty' => 0
        ];
        $this->saveCart();
    }

    private function init_section($table)
    {
        $this->cart->items->put($table, (object)[
            'productos' => collect([]),
            'subtotales' => collect([
                '858' => 0, //$U
                '840' => 0  //USD
            ]),
            'qty' => 0
        ]);
    }

    private function hasTable($table)
    {
        return $this->cart->items->has($table);
    }

    public function find($item)
    {
        if ($this->hasTable($item->getTable()))
            return $this->cart->items->{$item->getTable()}->productos->firstWhere('model.id', $item->id);

        return null;
    }

    private function calculateSubtotals(&$section)
    {
        $section->subtotales->transform(function ($subtotal) {
            return $subtotal * 0;
        });

        $section->productos->each(function ($p) use (&$section) {
            $section->subtotales[$p->model->getCurrency()] += $p->model->getFinalPriceAttribute() * $p->qty;
        });

        $this->calculateTotals();
    }

    private function calculateTotals()
    {
        $this->cart->totales->transform(function ($subtotal) {
            return $subtotal * 0;
        });

        $this->cart->items->map(function ($el) {
            $el->subtotales->each(function ($sub, $key) {
                $this->cart->totales[$key] += $sub;
            });
        });
    }

    public function put($item, $qty = 1)
    {
        if ($this->find($item))
            throw new \RuntimeException('Este elemento ya está contenido en el carro de compras');

        if (!$this->hasTable($item->getTable()))
            $this->init_section($item->getTable());

        $section = $this->cart->items->get($item->getTable());

        $section->productos->push((object)[
            'qty' => $qty,
            'model' => $item
        ]);

        $section->qty = $section->productos->count();

        $this->cart->qty += 1;

        $this->calculateSubtotals($section, $item);

        $this->cart->items->{$item->getTable()} = $section;
        $this->saveCart();
    }

    public function remove($item)
    {
        $producto = $this->find($item);
        if ($producto) {
            $filtered = $this->cart->items->{$item->getTable()}->productos->filter(function ($p) use ($item) {
                return $p->model->id !== $item->id;
            })->values();

            $this->cart->items->{$item->getTable()}->qty = $filtered->count();

            $this->cart->qty -= ($this->cart->items->{$item->getTable()}->productos->count() - $filtered->count());

            $this->cart->items->{$item->getTable()}->productos = $filtered;

            $this->calculateSubtotals($this->cart->items->{$item->getTable()});
            $this->saveCart();
        }
        else
            throw new \RuntimeException('Este elemento no se encuentra contenido en el carro de compras');
    }

    public function changeQty($item, $qty)
    {
        $changed = false;
        $this->cart->items->{$item->getTable()}->productos->map(function ($p) use ($item, $qty, &$changed) {
            if ($p->model->id === $item->id) {
                $p->qty = $qty;
                $changed = true;
            }
        });

        if ($changed) {
            $this->calculateSubtotals($this->cart->items->{$item->getTable()});
            $this->saveCart();
        }
    }

    private function saveCart()
    {
        Session::put('wocart', $this->cart);
        Session::save();
    }

    public function get()
    {
        return $this->cart;
    }

}
