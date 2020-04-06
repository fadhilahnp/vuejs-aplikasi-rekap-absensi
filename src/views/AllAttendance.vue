<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <b-card bg-variant="light" header="Pencarian" class="mt-3">
          <b-form @reset="onReset">
            <b-row>
              <b-col sm="8">
                <b-form-group id="input-month" label="Bulan:" label-for="input-month">
                  <b-form-select
                    id="month"
                    v-model="selectedMonth"
                    :options="month"
                    required
                  ></b-form-select>
                </b-form-group>
              </b-col>

              <b-col sm="4">
                <b-form-group id="input-year" label="Tahun:" label-for="input-year">
                  <b-form-select
                    id="year"
                    v-model="selectedYear"
                    :options="year"
                    required
                  ></b-form-select>
                </b-form-group>
              </b-col>
            </b-row>

            <b-button variant="success" v-on:click="onSubmit">Tampilkan</b-button>&nbsp;
            <b-button type="reset" variant="danger">Bersihkan</b-button>
          </b-form>
        </b-card>

        <b-card bg-variant="light" header-bg-variant="secondary" class="my-3" :busy="isBusy" v-if="isDataLoaded">
          <template v-slot:header >
            <download-excel
               class="float-right align-top"
               :data   = "attendance">
               <img src="download_icon.png">
             </download-excel>
            <h4 class="mb-0 text-white">{{selected}}</h4>
          </template>
          
          <b-table striped hover bordered sticky-header stickyColumn responsive sort-by="nama" :fields="fields" :items="attendance">
            <template v-slot:table-busy>
              <div class="text-center text-danger my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>Loading...</strong>
              </div>
            </template>
            <template v-slot:head(nama)="">
              <div style="width:12rem;">Nama</div>
            </template>
            <template v-slot:head(NilaiRataRata)="">
              <div class="text-nowrap">Nilai Rata-rata</div>
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
          month: null,
          year: null
        },
        fields:[{key:'nama',stickyColumn:true, isRowHeader:true}, '_1', '_2', '_3', '_4', '_5', '_6', '_7', '_8', '_9', '_10', '_11', '_12', '_13', '_14', '_15', '_16', '_17', '_18', '_19', '_20', '_21', '_22', '_23', '_24', '_25', '_26', '_27', '_28', '_29', '_30', '_31', 'NilaiRataRata'],
        month: [{ text: 'Pilih bulan', value: null },
                {value: '01', text: 'Januari'},
                {value: '02', text: 'Februari'},
                {value: '03', text: 'Maret'},
                {value: '04', text: 'April'},
                {value: '05', text: 'Mei'},
                {value: '06', text: 'Juni'},
                {value: '07', text: 'Juli'},
                {value: '08', text: 'Agustus'},
                {value: '09', text: 'September'},
                {value: '10', text: 'Oktober'},
                {value: '11', text: 'November'},
                {value: '12', text: 'Desember'}],
        year: [{ text: 'Pilih tahun', value: null }],
        selectedMonth: null,
        selectedYear: null,
        attendance:[],
        attendanceData: [],
        selected:'',
        isDataLoaded: false,
        isBusy: false
      }
  },
  mounted() {
    for (var i = 2019; i <= 2029; i++) {
      this.year.push(i);
    }
  },
  methods: {
    onSubmit() {
      let that = this;
      this.isBusy = true;

      axios
        .get("http://localhost/api/get_data.php?param=all&fromDate=" + that.selectedYear + "/" + that.selectedMonth +  "/01 00:01:00&toDate=" + that.selectedYear + "/" + that.selectedMonth + "/31 23:59:00")
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
    changeMonth(value) {
      let values = this.month.map(function(o) { return o.value });
      let index = values.indexOf(value) ;
      let choiceText = this.month[index].text;
      this.selected = choiceText;
    },
    reformatData() {
      let that = this;
      let tempID = '';
      let tempAnnotation = '';
      let total = 0;
      let totalTime = new Array();
      let tempData = {};
      let formattedData = [];

      this.attendanceData.forEach(function(data){
        if (tempID == data.pin) {
          tempID = data.pin;
          
          let idx = that.getIndexByDate(data.att_date);
          
          if (tempData[idx] == '') {
            totalTime.push(data.att_time);

            if (data.annotation == 2)
              tempData[idx] = that.regularScore(data.att_time);
            else
              tempData[idx] = that.specialScore(data.att_time);
          }
        } else {
          if (tempData.nama) {
            let avg = that.getAverageTimes(totalTime);

            if (tempAnnotation == 2)
              tempData.NilaiRataRata = that.regularScore(avg);
            else
              tempData.NilaiRataRata = that.specialScore(avg);

            formattedData.push(tempData);
            totalTime.length = 0;
          }

          tempID = data.pin;
          tempAnnotation = data.annotation;
          tempData = {nama:'',_1: '', _2: '', _3: '', _4: '', _5: '', _6: '', _7: '', _8: '', _9: '', _10: '', _11: '', _12: '', _13: '', _14: '', _15: '', _16: '', _17: '', _18: '', _19: '', _20: '', _21: '', _22: '', _23: '', _24: '', _25: '', _26: '', _27: '', _28: '', _29: '', _30: '', _31: '',NilaiRataRata:''};
          tempData.nama = data.name;
          
          let idx = that.getIndexByDate(data.att_date);

          if (tempData[idx] == '') {
            totalTime.push(data.att_time);

            if (data.annotation == 2)
              tempData[idx] = that.regularScore(data.att_time);
            else
              tempData[idx] = that.specialScore(data.att_time);
          }
        }

        total++;

        if (total == that.attendanceData.length) {
          that.attendance = formattedData;
          that.changeMonth(that.selectedMonth);
          that.isDataLoaded = true;
        }
      });
    },
    getIndexByDate(val) {
      let getIdx = new Date(val);
      return '_' + getIdx.getDate();
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
  }
}
</script>
