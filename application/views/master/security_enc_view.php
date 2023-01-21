<script src="<?= base_url() ?>assets/js/module/security/sjcl.js"></script>
<script>
    var decrypted_data = sjcl.decrypt(SECRET_ANDROID_KEY, $value);
<?= $decrypted_data; ?>
</script>
