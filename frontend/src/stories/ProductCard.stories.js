import ProductCard from '../components/ProductCard.vue';

export default {
    title: 'Components/ProductCard',
    component: ProductCard,
    argTypes: {
        product: {
            control: 'object',
        },
        isInCart: {
            control: 'boolean',
        },
        originalPrice: {
            control: 'number',
        },
        descriptionLength: {
            control: 'number',
        },
        'add-to-cart': { action: 'add-to-cart' },
    },
};

const defaultProduct = {
    id: 1,
    title: 'iPhone 16 5G 128GB',
    sku: 'PHONE-X-001',
    images: ['/src/assets/iphone-green.avif', '/src/assets/iphone-black.avif'],
    description: 'The Apple iPhone 16 5G 128GB represents the absolute pinnacle of the industry in terms of design and features. The powerful A18 chip ensures unparalleled speed and the 6.1-inch Super Retina XDR display reproduces everything with incredible clarity. Thanks to the long battery life, you can use your phone worry-free all day long.',
    price: 675.99,
};

export const Default = {
    args: {
        product: defaultProduct,
        isInCart: false,
        originalPrice: null,
        descriptionLength: 100,
    },
};

export const OnSale = {
    args: {
        product: {
            ...defaultProduct,
            price: 899.99,
        },
        isInCart: false,
        originalPrice: 999.99,
        descriptionLength: 100,
    },
};

export const InCart = {
    args: {
        product: defaultProduct,
        isInCart: true,
        originalPrice: null,
        descriptionLength: 100,
    },
};

export const NoImage = {
    args: {
        product: {
            ...defaultProduct,
            images: [],
        },
        isInCart: false,
        originalPrice: null,
        descriptionLength: 100,
    },
};

export const ShortDescription = {
    args: {
        product: {
            ...defaultProduct,
        },
        isInCart: false,
        originalPrice: null,
        descriptionLength: 50,
    },
};

export const NoDescription = {
    args: {
        product: {
            ...defaultProduct,
            description: null,
        },
        isInCart: false,
        originalPrice: null,
        descriptionLength: 100,
    },
};