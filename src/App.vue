<template>
  <div id="app">
    <nav class="navbar navbar-dark bg-primary">
      <a class="navbar-brand text-warning" href="#">
            <img src="favicon.ico" width="30" height="30" class="d-inline-block align-top"  alt="">
            ABSENSI
        </a>
    </nav>







    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand text-warning" href="#">
            <img src="favicon.ico" width="30" height="30" class="d-inline-block align-top"  alt="">
            ABSENSI
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <router-link to="/personal-attendance" class="nav-link active">Absensi Individu</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/all-attendance" class="nav-link active">Rekap Absensi</router-link>
                </li>
                <li class="nav-item">
                    <a v-bind:class="nav_class" href="#" v-on:click="resync">Sinkron Data</a>
                </li>
                <li class="nav-item">
                    <router-link to="/help" class="nav-link active">Bantuan</router-link>
                </li>
            </ul>
        </div>
    </nav> -->
    <router-view/>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  data() {
    return {
      nav_class:{'nav-link': true, active: true, disabled: false},
    }
  },
  methods: {
    resync() {
      let that = this;
      this.nav_class.disabled = true;
      this.nav_class.active = false;

      axios
        .get("http://localhost/api/sync_data.php")
        .then(function(resp) {
          that.nav_class.disabled = false;
          that.nav_class.active = true;

          if (resp.data.status == 'success') {
            that.$bvToast.toast(resp.data.message, {
              title: 'Sinkronisasi Data',
              variant: 'success',
              solid: true
            })
          } else if (resp.data.status == 'error') {
            that.nav_class.disabled = false;
            that.nav_class.active = true;

            that.$bvToast.toast('Gagal sinkronisasi data', {
              title: 'Sinkronisasi Data',
              variant: 'danger',
              solid: true
            })
          } 
        })
        .catch(function() {
          that.nav_class.disabled = false;
          that.nav_class.active = true;

          that.$bvToast.toast('Gagal sinkronisasi data', {
              title: 'Sinkronisasi Data',
              variant: 'danger',
              solid: true
            })
        });
    }
  }
}
</script>