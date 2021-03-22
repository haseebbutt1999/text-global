</div>
</div>
{{--Footer Start--}}

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>

<script src="{{ asset('polished_asset/select2/js/select2.full.js') }}"></script>


<script>
    $(document).ready(function() {
        $('.custom-select2').select2();
    });

    $('body').find('.ckeditors').each(function() {
        CKEDITOR.replace($(this).attr('id'));
    });
    $('.custom-ckeditor').find('.ckeditors').each(function() {
        CKEDITOR.replace($(this).attr('id'));
    });



</script>


</body>
</html>
{{--Footer End--}}
