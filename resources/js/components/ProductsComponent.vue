<template>
    <div>
        <h1>Products</h1>
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control" v-model="search" placeholder="Search products..." @input="fetchProducts">
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-primary" @click="openModal">Add Product</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity in Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{ product.name }}</td>
                    <td>{{ product.description }}</td>
                    <td>{{ product.price }}</td>
                    <td>{{ product.quantity_in_stock }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary mr-2" @click="editProduct(product)">Edit</button>
                        <button class="btn btn-sm btn-danger" @click="deleteProduct(product.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveProduct">
                            <div class="form-group">
                                <label for="productName">Name</label>
                                <input type="text" class="form-control" id="productName" v-model="newProduct.name" required>
                            </div>
                            <div class="form-group">
                                <label for="productDescription">Description</label>
                                <input type="text" class="form-control" id="productDescription" v-model="newProduct.description" required>
                            </div>
                            <div class="form-group">
                                <label for="productPrice">Price</label>
                                <input type="number" class="form-control" id="productPrice" v-model="newProduct.price" required>
                            </div>
                            <div class="form-group">
                                <label for="productQuantity">Quantity in Stock</label>
                                <input type="number" class="form-control" id="productQuantity" v-model="newProduct.quantity_in_stock" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Product Modal -->
        <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateProduct">
                            <div class="form-group">
                                <label for="editProductName">Name</label>
                                <input type="text" class="form-control" id="editProductName" v-model="editedProduct.name" required>
                            </div>
                            <div class="form-group">
                                <label for="editProductDescription">Description</label>
                                <input type="text" class="form-control" id="editProductDescription" v-model="editedProduct.description" required>
                            </div>
                            <div class="form-group">
                                <label for="editProductPrice">Price</label>
                                <input type="number" class="form-control" id="editProductPrice" v-model="editedProduct.price" required>
                            </div>
                            <div class="form-group">
                                <label for="editProductQuantity">Quantity in Stock</label>
                                <input type="number" class="form-control" id="editProductQuantity" v-model="editedProduct.quantity_in_stock" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            products: [],
            search: '',
            newProduct: {
                name: '',
                description: '',
                price: null,
                quantity_in_stock: null
            },
            editedProduct: {
                id: null,
                name: '',
                description: '',
                price: null,
                quantity_in_stock: null
            }
        };
    },
    methods: {
        fetchProducts() {
            axios.get('/api/products', { params: { search: this.search } })
                .then(response => {
                    this.products = response.data.data;
                });
        },
        deleteProduct(id) {
            axios.delete(`/api/products/${id}`)
                .then(response => {
                    this.fetchProducts();
                });
        },
        editProduct(product) {
            this.editedProduct = {
                id: product.id,
                name: product.name,
                description: product.description,
                price: product.price,
                quantity_in_stock: product.quantity_in_stock
            };
            $('#editProductModal').modal('show');
        },
        openModal() {
            this.newProduct = {
                name: '',
                description: '',
                price: null,
                quantity_in_stock: null
            };
            $('#addProductModal').modal('show');
        },
        saveProduct() {
            axios.post('/api/products', this.newProduct)
                .then(response => {
                    $('#addProductModal').modal('hide');
                    this.fetchProducts();
                });
        },
        updateProduct() {
            axios.put(`/api/products/${this.editedProduct.id}`, this.editedProduct)
                .then(response => {
                    $('#editProductModal').modal('hide');
                    this.fetchProducts();
                });
        }
    },
    mounted() {
        this.fetchProducts();
    }
};
</script>
