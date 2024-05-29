<template>
    <div>
        <h1>Orders</h1>
        <div class="row mb-3">
            <div class="col-md-6">
                <input
                    type="text"
                    class="form-control"
                    v-model="search"
                    placeholder="Search orders..."
                    @input="fetchOrders"
                />
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-primary" @click="openModal">
                    Add Order
                </button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Products</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="order in orders.data" :key="order.id">
                    <td>{{ order.customer.name }}</td>
                    <td>{{ parseFloat(order.total_amount).toFixed(2) }}</td>
                    <td :class="getStatusColor(order.order_status)">{{ order.order_status }}</td>
                    <td>
                        <ul>
                            <li v-for="product in order.products" :key="product.id">
                                {{ product.name }} - {{ product.pivot.quantity }}
                            </li>
                        </ul>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary mr-2" @click="editOrder(order)">Edit</button>
                        <button class="btn btn-sm btn-danger" @click="deleteOrder(order.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Add Order Modal -->
        <div
            class="modal fade"
            id="addOrderModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="addOrderModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOrderModalLabel">
                            Add New Order
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveOrder">
                            <div class="form-group">
                                <label for="customer">Customer</label>
                                <select
                                    class="form-control"
                                    v-model="newOrder.customer_id"
                                >
                                    <option v-for="customer in customers"
                                        :value="customer.id" :key="customer.id">
                                        {{ customer.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product">Product</label>
                                <select
                                    class="form-control"
                                    v-model="selectedProduct"
                                >
                                    <option v-for="product in products"
                                           :value="product.id"
                                           :key="product.id">
                                        {{ product.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    v-model="quantity"
                                />
                            </div>
                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="addProduct"
                            >
                                Add Product
                            </button>
                            <div class="form-group mt-3">
                                <label for="productList">Product List</label>
                                <ul
                                    class="list-group"
                                    v-if="newOrder.products.length > 0"
                                >
                                    <li
                                        class="list-group-item"
                                        v-for="(
                                            product, index
                                        ) in newOrder.products"
                                        :key="index"
                                    >
                                        {{ product.name }} - Quantity:
                                        {{ product.quantity }}
                                    </li>
                                </ul>
                                <p v-else>No products added</p>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="phone"
                                />
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>
                        </form>
                        <div v-if="loading" class="loader">Processing Payment...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Order Modal -->
        <div
            class="modal fade"
            id="editOrderModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="editOrderModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModalLabel">
                            Edit Order
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateOrderStatus">
                            <div class="form-group">
                                <label for="editCustomer">Customer</label>
                                <select
                                    class="form-control"
                                    v-model="editedOrder.customer_id"
                                    required
                                >
                                    <option v-for="customer in customers"
                                        :value="customer.id"
                                        :key="customer.id">
                                        {{ customer.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editTotalAmount">Total Amount</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    v-model="editedOrder.total_amount"
                                    readonly
                                />
                            </div>
                            <div class="form-group">
                                <label for="editStatus">Status</label>
                                <select
                                    class="form-control"
                                    v-model="editedOrder.order_status"
                                    required
                                >
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
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
            orders: [],
            customers: [],
            products: [],
            search: "",
            selectedProduct: null,
            quantity: null,
            phone: "",
            loading: false,
            newOrder: {
                customer_id: null,
                total_amount: 0,
                products: [],
                phone: "",
            },
            editedOrder: {
                id: null,
                customer_id: null,
                total_amount: 0,
                order_status: null,
            },
        };
    },
    methods: {
        getStatusColor(order_status) {
            switch(order_status) {
                case 'pending':
                    return 'text-warning';
                case 'processing':
                    return 'text-primary';
                case 'completed':
                    return 'text-success';
                default:
                    return '';
            }
        },
        fetchOrders() {
            axios
                .get("/api/orders", { params: { search: this.search } })
                .then((response) => {
                    this.orders = response.data;
                    console.log(this.orders);
                });
        },
        fetchCustomers() {
            axios.get("/api/customers").then((response) => {
                this.customers = response.data.data;
            });
        },
        fetchProducts() {
            axios.get("/api/products").then((response) => {
                this.products = response.data.data;
            });
        },
        deleteOrder(id) {
            axios.delete(`/api/orders/${id}`).then((response) => {
                this.fetchOrders();
            });
        },
        editOrder(order) {
            this.editedOrder = {
                id: order.id,
                customer_id: order.customer_id,
                total_amount: order.total_amount,
                order_status: order.order_status,
            };
            $("#editOrderModal").modal("show");
        },
        openModal() {
            this.newOrder = {
                customer_id: null,
                total_amount: 0,
                products: [],
            };
            $("#addOrderModal").modal("show");
        },
        addProduct() {
            if (this.selectedProduct && this.quantity) {
                const product = this.products.find(
                    (p) => p.id === parseInt(this.selectedProduct)
                );
                if (product) {
                    this.newOrder.products.push({
                        product_id: product.id,
                        name: product.name,
                        quantity: parseInt(this.quantity),
                    });
                    this.newOrder.total_amount +=
                        product.price * parseInt(this.quantity);
                    this.selectedProduct = null;
                    this.quantity = null;
                }
            }
        },
        initiateMpesaPayment() {
            // first save the order

            // then initiate payment with M-Pesa
            this.loading = true;
            this.newOrder.phone = this.phone;
            axios.post('/api/mpesa/stkpush', {
                phone: this.phone,
                amount: this.newOrder.total_amount,
                order_id: new Date().getTime()
            })
            .then(response => {
                if (response.data.ResponseCode === "0") {
                    this.saveOrder();
                } else {
                    this.loading = false;
                    alert("Payment failed. Please try again.");
                }
            })
            .catch(error => {
                this.loading = false;
                console.error("Payment error:", error);
                alert("Payment error. Please try again.");
            });
        },
        saveOrder() {
            axios.post("/api/orders", {
                ...this.newOrder,
                phone: this.phone
            }).then((response) => {
                this.loading = false;
                $("#addOrderModal").modal("hide");
                // 
                this.fetchOrders();
            }).catch(error => {
                this.loading = false;
                console.error("Order save error:", error);
                alert("Order save error. Please try again.");
            });
        },
        updateOrderStatus() {
            axios
                .put(`/api/orders/${this.editedOrder.id}`, {
                    order_status: this.editedOrder.order_status,
                })
                .then((response) => {
                    $("#editOrderModal").modal("hide");
                    this.fetchOrders();
                });
        },
    },
    mounted() {
        this.fetchOrders();
        this.fetchCustomers();
        this.fetchProducts();
    },
};
</script>
