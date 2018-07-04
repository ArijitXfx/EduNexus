
<?php echo validation_errors(); ?>

<?php echo form_open('edunexus/discussion/create'); ?>

    <label for="title">Title</label>
    <input type="input" name="title"/><br/>
    <label for="course_id">Course Id</label>
    <input type="input" name="course_id" /><br/>
    <label for="description">Description</label>
    <textarea name="description"></textarea><br/>

    <input type="submit" name="submit" value="Create new thread" />

</form>