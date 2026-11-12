export interface User {
    id: number;
    name: string;
    email: string;
    role: 'admin' | 'user';
    email_verified_at?: string;
    created_at?: string;
}

export interface Category {
    id: number;
    name: string;
    slug: string;
    description?: string;
    products_count?: number;
    created_at?: string;
    updated_at?: string;
}

export interface Product {
    id: number;
    category_id?: number | null;
    category?: Category;
    name: string;
    slug: string;
    variant: string;
    short_description?: string;
    description?: string;
    price: number;
    weight: number;
    main_image?: string;
    gallery?: string[];
    ingredients?: string;
    nutrition_information?: string;
    allergen_information?: string;
    preparation_instructions?: string;
    storage_instructions?: string;
    is_active: boolean;
    is_featured: boolean;
    created_at?: string;
    updated_at?: string;
}

export interface Article {
    id: number;
    title: string;
    slug: string;
    category: string;
    excerpt?: string;
    content: string;
    thumbnail?: string;
    published_at?: string;
    status: 'draft' | 'published';
    created_at?: string;
    updated_at?: string;
}

export interface OrderItem {
    id: number;
    order_id: number;
    product_id?: number | null;
    product?: Product;
    product_name_snapshot: string;
    variant_snapshot?: string;
    price: number;
    quantity: number;
    subtotal: number;
    created_at?: string;
}

export interface Order {
    id: number;
    order_number: string;
    customer_name: string;
    whatsapp_number: string;
    address: string;
    notes?: string;
    subtotal: number;
    total: number;
    status: 'pending' | 'confirmed' | 'processing' | 'completed' | 'cancelled';
    items?: OrderItem[];
    created_at: string;
    updated_at?: string;
}

export interface CartItem {
    product_id: number;
    name: string;
    slug: string;
    variant: string;
    price: number;
    weight: number;
    main_image: string;
    quantity: number;
    subtotal: number;
}

export interface SiteSettings {
    whatsapp_number?: string;
    store_name?: string;
    store_address?: string;
    store_email?: string;
    announcement_text?: string;
    instagram_url?: string;
    facebook_url?: string;
    [key: string]: string | undefined;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User | null;
    };
    cart: {
        items: Record<number, CartItem> | CartItem[];
        total_items: number;
        subtotal: number;
        total: number;
    };
    site_settings: SiteSettings;
    flash?: {
        success?: string;
        error?: string;
    };
};

