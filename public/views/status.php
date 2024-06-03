
    <?php $this->layout('layout'); ?>
    <main id="js-page-content" role="main" class="page-content mt-3">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-sun'></i> Установить статус
            </h1>

        </div>
        <?php foreach($user as $user_info): ?>
        <form action="/update_status/<?php echo $user_info['id']; ?>" method="post">
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Установка текущего статуса</h2>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- status -->
                                        <div class="form-group">
                                            <label class="form-label" for="example-select">Выберите статус</label>
                                            <select name="status" class="form-control" id="example-select">
                                                <option value="online" <?php echo $user_info['status'] == 'online' ? 'selected' : '';?>>Онлайн</option>
                                                <option value="away" <?php echo $user_info['status'] == 'away' ? 'selected' : '';?>>Отошел</option>
                                                <option value="busy" <?php echo $user_info['status'] == 'busy' ? 'selected' : '';?>>Не беспокоить</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                        <button type="submit" class="btn btn-warning">Set Status</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
        <?php endforeach; ?>
    </main>

    