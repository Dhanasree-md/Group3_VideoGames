<?php

class CartItem {
    public $gameId;
    public $title;
    public $unitPrice;
    public $quantity;

    public function __construct($gameId, $title, $unitPrice, $quantity) {
        $this->gameId = $gameId;
        $this->title = $title;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }

    public function getTotalPrice() {
        return $this->unitPrice * $this->quantity;
    }
}

class Cart {
    private $items = [];

    public function addItem($gameId, $title, $unitPrice, $quantity = 1) {
        if (isset($this->items[$gameId])) {
            $this->items[$gameId]->quantity += $quantity;
        } else {
            $this->items[$gameId] = new CartItem($gameId, $title, $unitPrice, $quantity);
        }
    }

    public function updateItemQuantity($gameId, $quantity) {
        if (isset($this->items[$gameId])) {
            if ($quantity > 0) {
                $this->items[$gameId]->quantity = $quantity;
            } else {
                $this->removeItem($gameId);
            }
        }
    }

    public function removeItem($gameId) {
        if (isset($this->items[$gameId])) {
            unset($this->items[$gameId]);
        }
    }

    public function clearCart() {
        $this->items = [];
    }

    public function getItems() {
        return $this->items;
    }

    public function getTotalAmount() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getTotalPrice();
        }
        return $total;
    }

    public function isEmpty() {
        return empty($this->items);
    }
}
?>
