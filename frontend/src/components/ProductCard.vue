<template>
    <div class="product-card">
        <div class="product-card__image-container">
            <img v-if="product.images && product.images.length > 0" :src="primaryImage" :alt="product.title"
                class="product-card__image" />
            <div v-else class="product-card__image-placeholder">
                No image available
            </div>
            <div v-if="isOnSale" class="product-card__sale-tag">SALE</div>
        </div>

        <div class="product-card__content">
            <h3 class="product-card__title">{{ product.title }}</h3>
            <p v-if="product.description" class="product-card__description">
                {{ truncatedDescription }}
            </p>
            <div class="product-card__price-container">
                <span v-if="isOnSale" class="product-card__original-price">
                    {{ formatPrice(originalPrice) }}
                </span>
                <span class="product-card__price">{{ formatPrice(product.price) }}</span>
            </div>

            <div class="product-card__actions">
                <button class="product-card__button" @click="addToCart" :disabled="isInCart">
                    {{ isInCart ? 'In Cart' : 'Add to Cart' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ProductCard',

    props: {
        product: {
            type: Object,
            required: true,
            validator: (product) => {
                return (
                    product.title !== undefined &&
                    product.price !== undefined
                );
            }
        },
        isInCart: {
            type: Boolean,
            default: false
        },
        originalPrice: {
            type: Number,
            default: null
        },
        descriptionLength: {
            type: Number,
            default: 100
        }
    },

    computed: {
        primaryImage() {
            if (!this.product.images || this.product.images.length === 0) {
                return '';
            }

            const images = typeof this.product.images === 'string'
                ? JSON.parse(this.product.images)
                : this.product.images;

            return images[0];
        },

        truncatedDescription() {
            if (!this.product.description) return '';

            if (this.product.description.length <= this.descriptionLength) {
                return this.product.description;
            }

            return this.product.description.substring(0, this.descriptionLength) + '...';
        },

        isOnSale() {
            return this.originalPrice !== null && this.originalPrice > this.product.price;
        }
    },

    methods: {
        formatPrice(price) {
            return new Intl.NumberFormat('fi-FI', {
                style: 'currency',
                currency: 'EUR'
            }).format(price);
        },

        addToCart() {
            if (!this.isInCart) {
                this.$emit('add-to-cart', this.product);
            }
        }
    }
}
</script>

<style scoped>
.product-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    max-width: 320px;
    background-color: #fff;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.product-card__image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
    background-color: #f7fafc;
}

.product-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-card__image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #a0aec0;
    font-size: 14px;
}

.product-card__sale-tag {
    position: absolute;
    top: 12px;
    right: 12px;
    background-color: #ed8936;
    color: white;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: bold;
    border-radius: 4px;
}

.product-card__content {
    padding: 16px;
}

.product-card__title {
    margin: 0 0 8px;
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
}

.product-card__description {
    margin: 0 0 16px;
    font-size: 14px;
    color: #4a5568;
    line-height: 1.4;
}

.product-card__price-container {
    display: flex;
    align-items: center;
    margin-bottom: 16px;
    gap: 8px;
}

.product-card__price {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
}

.product-card__original-price {
    font-size: 14px;
    text-decoration: line-through;
    color: #a0aec0;
}

.product-card__actions {
    display: flex;
    justify-content: space-between;
}

.product-card__button {
    background-color: #4299e1;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
    width: 100%;
}

.product-card__button:hover:not(:disabled) {
    background-color: #3182ce;
}

.product-card__button:disabled {
    background-color: #cbd5e0;
    cursor: not-allowed;
}
</style>