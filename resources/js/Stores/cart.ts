import { defineStore } from 'pinia';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export const useCartStore = defineStore('cart', () => {
    const isDrawerOpen = ref(false);
    const isLoading = ref(false);

    function openDrawer() {
        isDrawerOpen.value = true;
    }

    function closeDrawer() {
        isDrawerOpen.value = false;
    }

    function toggleDrawer() {
        isDrawerOpen.value = !isDrawerOpen.value;
    }

    function addToCart(productId: number, quantity = 1, showDrawer = true) {
        isLoading.value = true;
        router.post(
            '/keranjang/tambah',
            { product_id: productId, quantity },
            {
                preserveScroll: true,
                onSuccess: () => {
                    isLoading.value = false;
                    if (showDrawer) {
                        openDrawer();
                    }
                },
                onError: () => {
                    isLoading.value = false;
                },
            }
        );
    }

    function updateQuantity(productId: number, quantity: number) {
        if (quantity < 1) {
            removeItem(productId);
            return;
        }

        isLoading.value = true;
        router.patch(
            `/keranjang/${productId}`,
            { quantity },
            {
                preserveScroll: true,
                onFinish: () => {
                    isLoading.value = false;
                },
            }
        );
    }

    function removeItem(productId: number) {
        isLoading.value = true;
        router.delete(`/keranjang/${productId}`, {
            preserveScroll: true,
            onFinish: () => {
                isLoading.value = false;
            },
        });
    }

    return {
        isDrawerOpen,
        isLoading,
        openDrawer,
        closeDrawer,
        toggleDrawer,
        addToCart,
        updateQuantity,
        removeItem,
    };
});

