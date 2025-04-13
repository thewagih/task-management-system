  @csrf
  <div class="form-group">
      <label for="description">Title</label>
      <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title"
          placeholder="Enter title" value="{{ old('title', $task->title) }}">

      @error('title')
          <div class="invalid-feedback">{{ $message }}</div>
      @enderror
  </div>


  <div class="form-group mt-3">
      <label for="description">Description</label>
      <textarea class="form-control  @error('description') is-invalid @enderror" id="description" name="description"
          cols="30" rows="10">{{ old('description', $task->description) }}</textarea>
      @error('description')
          <div class="invalid-feedback">{{ $message }}</div>
      @enderror
  </div>

  <button type="submit" class="btn btn-primary mt-3">Submit</button>
  <button type="reset" class="btn btn-warning mt-3">Reset</button>
