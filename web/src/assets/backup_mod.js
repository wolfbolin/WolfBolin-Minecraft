export default {
  get_data: function () {
    let that = this;
    this.$http.get(this.$wb_host + '/backup/list').then(function (response) {
      if (response.data.status === 'success') {
        that.backup_list = response.data.data;
      } else {
        that.$notify({
          title: '警告',
          message: response.data.info,
          type: 'warning',
          position: 'bottom-right'
        });
      }
    }).catch(function (err) {
        that.$notify.error({
          title: '错误',
          message: '您似乎与服务器断开链接',
          position: 'bottom-right'
        });
      }
    );
  },

  auth_password: function () {
    this.$prompt('请输入管理密钥', '提示', {
      confirmButtonText: '确定',
      cancelButtonText: '取消',
      inputType: 'password',
      inputPattern: /^[a-zA-Z0-9~!@#$%^&*()_+`\-={}|[\]:";'<>?,./]+$/,
      inputErrorMessage: '密钥格式不正确'
    }).then(({value}) => {
      this.auth_code = value;
      this.$message({
        type: 'success',
        message: '修改成功'
      });
    }).catch(() => {
      this.$message({
        type: 'info',
        message: '取消输入'
      });
    });
  },

  get_download: function (index, row) {
    if (this.auth_code.length === 0) {
      this.$notify.error({
        title: '错误',
        message: '请输入正确的管理密钥',
        position: 'bottom-right'
      });
      return;
    }
    this.$notify.success({
      title: '提示',
      message: '正在下载存档' + row.index,
      position: 'bottom-right'
    });

    let that = this;
    this.$http.get(
      this.$wb_host + '/backup/' + row.index,
      {
        headers: {'X-Auth-Token': this.auth_code}
      }
    ).then(function (response) {
      if (response.data.status === 'success') {
        let http_data = response.data;

        that.$confirm('已为您预热数据文件\n请在' + http_data.ttl + '内完成下载。', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          window.open(http_data.url);
        }).catch(() => {
          that.$message({
            type: 'info',
            message: '已取消下载'
          });
        });

      } else {
        that.$notify({
          title: '警告',
          message: response.data.info,
          type: 'warning',
          position: 'bottom-right'
        });
      }
    }).catch(function (err) {
        console.log(err);
        that.$notify.error({
          title: '错误',
          message: err,
          position: 'bottom-right'
        });
      }
    );
  },

  post_delete: function (index, row) {
    if (this.auth_code.length === 0) {
      this.$notify.error({
        title: '错误',
        message: '请输入正确的管理密钥',
        position: 'bottom-right'
      });
      return;
    }
    this.$notify({
      title: '警告',
      message: '正在删除存档' + row.index,
      type: 'warning',
      position: 'bottom-right'
    });

    let that = this;
    this.$http.delete(
      this.$wb_host + '/backup/' + row.index,
      {
        headers: {'X-Auth-Token': this.auth_code}
      }
    ).then(function (response) {
      if (response.data.status === 'success') {
        that.backup_list.splice(index, 1);
        that.$notify.success({
          title: '成功',
          message: '已成功删除存档',
          position: 'bottom-right'
        });
      } else {
        that.$notify({
          title: '警告',
          message: response.data.info,
          type: 'warning',
          position: 'bottom-right'
        });
      }
    }).catch(function (err) {
        console.log(err);
        that.$notify.error({
          title: '错误',
          message: err,
          position: 'bottom-right'
        });
      }
    );
  },

  add_backup: function () {
    if (this.auth_code.length === 0) {
      this.$notify.error({
        title: '错误',
        message: '请输入正确的管理密钥',
        position: 'bottom-right'
      });
      return;
    }
    this.$notify.success({
      title: '提示',
      message: '正在添加存档',
      position: 'bottom-right'
    });

    let that = this;
    this.$http.post(
      this.$wb_host + '/backup/world',
      {},
      {
        headers: {'X-Auth-Token': this.auth_code}
      }
    ).then(function (response) {
      if (response.data.status === 'success') {
        that.$notify.success({
          title: '成功',
          message: '已成功添加存档',
          position: 'bottom-right'
        });
        that.getBackupList();
      } else {
        that.$notify({
          title: '警告',
          message: response.data.info,
          type: 'warning',
          position: 'bottom-right'
        });
      }
    }).catch(function (err) {
        console.log(err);
        that.$notify.error({
          title: '错误',
          message: err,
          position: 'bottom-right'
        });
      }
    );
  }
}
