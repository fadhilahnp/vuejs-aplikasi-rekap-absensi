<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <b-card bg-variant="light" header="Pencarian" class="mt-3">
          <b-form @reset="onReset">
            
            <b-form-group id="input-name" label="Nama:" label-for="input-name">
              <b-form-select
                id="name"
                v-model="form.employee_id"
                required
              >
                <b-form-select-option :value='null'>Pilih Nama</b-form-select-option>
                <b-form-select-option v-for="(item, index) in names" :value="item.pin" v-bind:key="index">{{ item.name }}</b-form-select-option>
              </b-form-select>
            </b-form-group>

            <b-row>
              <b-col sm="6">
                <b-form-group id="input-date-from" label="Dari tanggal:" label-for="input-date-from">
                  <b-form-input
                    id="date-from"
                    type="date"
                    v-model="form.date_from"
                    required
                  ></b-form-input>
                </b-form-group>
              </b-col>

              <b-col sm="6">
                <b-form-group id="input-date-until" label="Hingga tanggal:" label-for="input-date-until">
                  <b-form-input
                    id="date-until"
                    type="date"
                    v-model="form.date_until"
                    required
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>

            <b-button variant="success" v-on:click="onSubmit">Tampilkan</b-button>&nbsp;
            <b-button type="reset" variant="danger">Bersihkan</b-button>
          </b-form>
        </b-card>

        <b-card bg-variant="light" class="my-3" :busy="isBusy" :title="name" :sub-title="month" :footer="'Nilai absensi: ' + totalScore" :footer-bg-variant="footerColor" v-if="isDataLoaded">
          <b-table striped hover :items="attendance">
            <template v-slot:table-busy>
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
            </template>
          </b-table>
        </b-card>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
        form: {
          employee_id: null,
          date_from: null,
          date_until: null
        },
        names: [{ text: 'Pilih nama', value: null }],
        attendanceData: [],
        attendance: [],
        name: null,
        month: null,
        totalScore: null,
        isDataLoaded: false,
        isBusy:false,
        footerColor: 'success'
      }
  },
  mounted() {
    this.fetchEmployee();
  },
  methods: {
    fetchEmployee() {
      let that = this;
      axios
        .get("http://localhost/api/get_data.php?param=name")
        .then(function(resp) {
          if (resp.data.status == 'success') {
            that.names = resp.data.data;
          }
        })
        .catch(function() {
          that.$bvToast.toast('Terjadi kesalahan', {
              title: 'ERROR!',
              variant: 'danger',
              solid: true
            });
        });
    },
    onSubmit() {
      let that = this;
      this.isBusy = true;

      axios
        .get("http://localhost/api/get_data.php?param=single&pin=" + that.form.employee_id + "&fromDate=" + that.form.date_from +  " 00:01:00&toDate=" + that.form.date_until + " 23:59:00")
        .then(function(resp) {
          if (resp.data.status == 'success') {
            that.attendanceData = resp.data.data;
            that.reformatData();
            that.isBusy = false;
          }
        })
        .catch(function() {
          that.$bvToast.toast('Terjadi kesalahan', {
              title: 'ERROR!',
              variant: 'danger',
              solid: true
            });
        });
    },
    onReset() {
      
    },
    reformatData() {
      const that = this;
      let formattedData = [];
      let tempData = {};
      let tempDate = '';
      let totalTime = new Array();
      let total = 0;

      this.attendanceData.forEach(function(data){
        if (tempDate == data.att_date) {
          tempData.jamPulang = data.att_time;
        } else {
          if (tempData.hasOwnProperty('jamMasuk')) {
            if (tempData.tanggal !== '')
              formattedData.push(tempData);
          }

          tempDate = data.att_date;

          tempData = {tanggal:'', jamMasuk:'', jamPulang: '', nilai: ''};
          tempData.tanggal = data.att_date;

          if (data.status == 0)
            tempData.jamMasuk = data.att_time;
          else if (data.status == 1)
            tempData.jamPulang = data.att_time;

          if (data.annotation == 1)
            tempData.nilai = that.specialScore(data.att_time);
          else
            tempData.nilai = that.regularScore(data.att_time);

          if (data.status == 0)
            totalTime.push(data.att_time);
        }

        total++;

        if (total == that.attendanceData.length) {
          if (tempData.hasOwnProperty('jamMasuk')) {
            if (tempData.jamMasuk !== '')
              formattedData.push(tempData);
          }

          that.name = that.attendanceData[0].name;
          that.setMonth(that.attendanceData[0].att_date);
          that.attendance = formattedData;
          that.isDataLoaded = true;

          let tempTotal = that.getAverageTimes(totalTime);

          if (that.attendanceData[0].annotation == 2)
            that.totalScore = that.regularScore(tempTotal);
          else
            that.totalScore = that.specialScore(tempTotal);

          if (that.totalScore == 'A' || that.totalScore == 'B')
            that.footerColor = 'success'
          else if (that.totalScore == 'C')
            that.footerColor = 'warning'
          else if (that.totalScore == 'D')
            that.footerColor = 'danger'
        }
      });
    },
    regularScore(time) {
      if (time <= '07:10:00')
        return 'A';
      else if (time <= '07:24:00')
        return 'B';
      else if (time <= '07:30:00')
        return 'C';
      else
        return 'D';
    },
    specialScore(time) {
      if (time <= '07:00:00')
        return 'A';
      else if (time <= '07:15:00')
        return 'B';
      else if (time <= '07:24:00')
        return 'C';
      else
        return 'D';
    },
    getAverageTimes(arr) {
      var times = [3600, 60, 1],
        parts = arr.map(function (s) {
            return s.split(':').reduce(function (s, v, i) {
                return s + times[i] * v;
            }, 0);
        }),
        avg = Math.round(parts.reduce(function (a, b) {
            return a + b;
        }, 0) / parts.length);

      return times
        .map(function (t) {
            var value = Math.floor(avg / t);
            avg %= t;
            return value;
        })
        .map(function (v) {
            return v.toString().padStart(2, 0);
         })
        .join(':');
    },
    setMonth(data) {
      const that = this;
      let tempDate = new Date(data);
      let date = tempDate.getMonth();

      switch(date) {
        case 0:
          that.month = "Januari";
          break;
        case 1:
          that.month = "Februari";
          break;
        case 2:
          that.month = "Maret";
          break;
        case 3:
          that.month = "April";
          break;
        case 4:
          that.month = "Mei";
          break;
        case 5:
          that.month = "Juni";
          break;
        case 6:
          that.month = "Juli";
          break;
        case 7:
          that.month = "Agustus";
          break;
        case 8:
          that.month = "September";
          break;
        case 9:
          that.month = "Oktober";
          break;
        case 10:
          that.month = "November";
          break;
        case 11:
          that.month = "Desember";
          break;
        default:
          that.month = "Tidak diketahui";
      }
    }
  }
}
</script>
