import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    discount: 0,
    taxRate: 0.1, // 10% tax
  }),

  getters: {
    subtotal: (state) => {
      return state.items.reduce((total, item) => {
        return total + (item.price * item.quantity);
      }, 0);
    },

    tax: (state) => {
      return state.subtotal * state.taxRate;
    },

    grandTotal: (state) => {
      return state.subtotal + state.tax - state.discount;
    },

    itemCount: (state) => {
      return state.items.reduce((total, item) => total + item.quantity, 0);
    },
  },

  actions: {
    addItem(product, quantity = 1) {
      const existingItem = this.items.find(item => item.product_id === product.id);

      if (existingItem) {
        // Check stock before adding
        if (existingItem.quantity + quantity > product.stock) {
          throw new Error(`Only ${product.stock} items available in stock`);
        }
        existingItem.quantity += quantity;
      } else {
        // Check stock for new item
        if (quantity > product.stock) {
          throw new Error(`Only ${product.stock} items available in stock`);
        }
        
        this.items.push({
          product_id: product.id,
          name: product.name,
          price: product.price,
          quantity: quantity,
          stock: product.stock,
          image: product.image,
        });
      }
    },

    updateQuantity(productId, quantity) {
      const item = this.items.find(item => item.product_id === productId);
      
      if (item) {
        if (quantity <= 0) {
          this.removeItem(productId);
        } else if (quantity > item.stock) {
          throw new Error(`Only ${item.stock} items available in stock`);
        } else {
          item.quantity = quantity;
        }
      }
    },

    removeItem(productId) {
      const index = this.items.findIndex(item => item.product_id === productId);
      if (index > -1) {
        this.items.splice(index, 1);
      }
    },

    applyDiscount(amount) {
      this.discount = Math.max(0, amount);
    },

    clearCart() {
      this.items = [];
      this.discount = 0;
    },

    getCartData() {
      return {
        items: this.items.map(item => ({
          product_id: item.product_id,
          quantity: item.quantity,
          price: item.price,
        })),
        subtotal: this.subtotal,
        tax: this.tax,
        discount: this.discount,
        grand_total: this.grandTotal,
      };
    },
  },
});
