/* global learninghubSync */
(() => {
    'use strict';

    const start = document.getElementById('learninghub-sync-start');
    const pause = document.getElementById('learninghub-sync-pause');
    const reset = document.getElementById('learninghub-sync-reset');
    const status = document.getElementById('learninghub-sync-status');
    const error = document.getElementById('learninghub-sync-error');
    let job = learninghubSync.status;
    let running = false;
    let pauseRequested = false;

    function render() {
        status.textContent = job.message + (job.started ? ' Started: ' + job.started + '.' : '');
        const unfinished = job.stage !== 'idle' && job.stage !== 'done';
        start.textContent = unfinished ? 'Resume' : 'Start Systems Sync';
        start.disabled = running;
        pause.hidden = !running;
        pause.disabled = pauseRequested;
        reset.hidden = running || !unfinished;
    }

    async function request(command) {
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 35000);
        try {
            const response = await fetch(learninghubSync.url, {
                method: 'POST',
                credentials: 'same-origin',
                signal: controller.signal,
                body: new URLSearchParams({
                    action: 'learninghub_sync',
                    nonce: learninghubSync.nonce,
                    command,
                    job_id: job.id || '',
                }),
            });
            let result;
            try {
                result = await response.json();
            } catch (failure) {
                throw new Error('The server did not finish this request (HTTP ' + response.status + ').');
            }
            if (!result.success) {
                throw new Error(result.data?.message || 'The request failed. Reload if your login has expired.');
            }
            job = result.data;
            render();
        } finally {
            clearTimeout(timeout);
        }
    }

    start.addEventListener('click', async () => {
        running = true;
        pauseRequested = false;
        error.textContent = '';
        render();
        try {
            // Returns an existing unfinished job if another tab already started it.
            await request('start');
            while (!pauseRequested && job.stage !== 'done') {
                await request('step');
            }
        } catch (failure) {
            error.textContent = failure.message + ' Progress is saved. Wait a moment, then choose Resume. If this repeats, report the stage shown above.';
        } finally {
            running = false;
            render();
        }
    });

    pause.addEventListener('click', () => {
        pauseRequested = true;
        render();
    });

    reset.addEventListener('click', async () => {
        running = true;
        error.textContent = '';
        render();
        try {
            await request('reset');
        } catch (failure) {
            error.textContent = failure.message;
        } finally {
            running = false;
            render();
        }
    });

    render();
})();
