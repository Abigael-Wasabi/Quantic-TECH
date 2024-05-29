<template>
    <div>
        <h1>Customers</h1>
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control" v-model="search" placeholder="Search customers..." @input="fetchCustomers">
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-primary" @click="openModal">Add Customer</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="customer in customers" :key="customer.id">
                    <td>{{ customer.name }}</td>
                    <td>{{ customer.email }}</td>
                    <td>{{ customer.phone_number }}</td>
                    <td>{{ customer.address }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary mr-2" @click="editCustomer(customer)">Edit</button>
                        <button class="btn btn-sm btn-danger" @click="deleteCustomer(customer.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Add Customer Modal -->
        <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveCustomer">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" v-model="newCustomer.name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" v-model="newCustomer.email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone_number" v-model="newCustomer.phone_number" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" v-model="newCustomer.address" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Customer Modal -->
        <div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateCustomer">
                            <div class="form-group">
                                <label for="editName">Name</label>
                                <input type="text" class="form-control" id="editName" v-model="editedCustomer.name" required>
                            </div>
                            <div class="form-group">
                                <label for="editEmail">Email</label>
                                <input type="email" class="form-control" id="editEmail" v-model="editedCustomer.email" required>
                            </div>
                            <div class="form-group">
                                <label for="editPhone">Phone</label>
                                <input type="text" class="form-control" id="editPhone" v-model="editedCustomer.phone_number" required>
                            </div>
                            <div class="form-group">
                                <label for="editAddress">Address</label>
                                <input type="text" class="form-control" id="editAddress" v-model="editedCustomer.address" required>
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
            customers: [],
            search: '',
            newCustomer: {
                name: '',
                email: '',
                phone_number: '',
                address: ''
            },
            editedCustomer: {
                id: null,
                name: '',
                email: '',
                phone_number: '',
                address: ''
            }
        };
    },
    methods: {
        fetchCustomers() {
            axios.get('/api/customers', { params: { search: this.search } })
                .then(response => {
                    this.customers = response.data.data;
                });
        },
        deleteCustomer(id) {
            axios.delete(`/api/customers/${id}`)
                .then(response => {
                    this.fetchCustomers();
                });
        },
        editCustomer(customer) {
            this.editedCustomer = {
                id: customer.id,
                name: customer.name,
                email: customer.email,
                phone_number: customer.phone_number,
                address: customer.address
            };
            $('#editCustomerModal').modal('show');
        },
        openModal() {
            this.newCustomer = {
                name: '',
                email: '',
                phone_number: '',
                address: ''
            };
            $('#addCustomerModal').modal('show');
        },
        saveCustomer() {
            axios.post('/api/customers', this.newCustomer)
                .then(response => {
                    $('#addCustomerModal').modal('hide');
                    this.fetchCustomers();
                });
        },
        updateCustomer() {
            axios.put(`/api/customers/${this.editedCustomer.id}`, this.editedCustomer)
                .then(response => {
                    $('#editCustomerModal').modal('hide');
                    this.fetchCustomers();
                });
        }
    },
    mounted() {
        this.fetchCustomers();
    }
};
</script>
