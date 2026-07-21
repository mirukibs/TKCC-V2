<template>
  <div id="app">
    <header class="app-header">
      <h1>TKCC Church Management System</h1>
      <nav>
        <router-link to="/">Home</router-link>
        <router-link to="/members">Members</router-link>
        <router-link to="/households">Households</router-link>
        <router-link to="/communities">Communities</router-link>
      </nav>
    </header>
    <main>
      <router-view></router-view>
    </main>
  </div>
</template>

<style scoped>
.app-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background-color: var(--color-primary);
  color: white;
}
nav a {
  color: white;
  margin-left: 1rem;
  text-decoration: none;
}
nav a:hover {
  text-decoration: underline;
}
main {
  padding: 2rem;
}
</style>
