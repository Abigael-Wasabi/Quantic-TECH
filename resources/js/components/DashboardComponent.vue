<template>
  <div>
    <h1 class="display-4 mb-4">Dashboard</h1>

    <!-- Sales Summary -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        <h2 class="h5 mb-0">Sales Summary</h2>
      </div>
      <div class="card-body">
        <p class="lead mb-1">Total Customers: <span class="font-weight-bold">{{ summary.total_customers }}</span></p>
        <p class="lead mb-1">Total Orders: <span class="font-weight-bold">{{ summary.total_orders }}</span></p>
        <p class="lead mb-0">Total Revenue: <span class="font-weight-bold">$ {{ summary.total_revenue }}</span></p>
      </div>
    </div>

    <!-- Top Selling Products -->
    <div class="card mb-4">
      <div class="card-header bg-success text-white">
        <h2 class="h5 mb-0">Top Selling Products</h2>
      </div>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item" v-for="product in summary.top_selling_products" :key="product.id">
            {{ product.name }} - <span class="font-weight-bold">{{ product.orders_count }}</span> sold
          </li>
        </ul>
      </div>
    </div>

    <!-- Recent Orders -->
    <div class="card">
      <div class="card-header bg-info text-white">
        <h2 class="h5 mb-0">Recent Orders</h2>
      </div>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item" v-for="order in summary.recent_orders" :key="order.id">
            {{ order.customer.name }} - Total Amount: <span class="font-weight-bold">$ {{ order.total_amount }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      summary: {
        total_customers: 0,
        total_orders: 0,
        total_revenue: 0,
        top_selling_products: [],
        recent_orders: []
      }
    };
  },
  methods: {
    fetchDashboardData() {
      axios.get('/api/dashboard')
        .then(response => {
          this.summary = response.data;
        })
        .catch(error => {
          console.error('Error fetching dashboard data:', error);
        });
    }
  },
  mounted() {
    this.fetchDashboardData();
  }
};
</script>

<style scoped>
/* Add custom styling for the dashboard if needed */
</style>
