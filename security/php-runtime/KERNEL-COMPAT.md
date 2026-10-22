# Compatibility with legacy Docker seccomp

Modern glibc probes clone3 for thread creation. The legacy Docker 17.05 profile returns EPERM for this unknown syscall; glibc falls back to clone only on ENOSYS. Consequently curl threaded DNS resolution can fail while ordinary DNS tools work.

The statically compiled x86_64 wrapper stacks an additional seccomp filter: clone3 (syscall 435) returns ENOSYS; other calls retain inherited Docker decisions. Filter stacking selects the least permissive action; for equal ERRNO actions, the newest filter supplies the errno. No previously denied syscall becomes allowed. Architecture mismatch terminates the process. Startup sets NO_NEW_PRIVS and exits 70 if the filter cannot be installed; the Docker seccomp profile remains enabled. Apache and its descendants inherit these restrictions.

This wrapper needs Linux x86_64 and is built off-host. It is not a replacement for upgrading the legacy host/kernel/Docker. NO_NEW_PRIVS also prevents privilege gains through setuid/file capabilities on exec; the application does not require those gains.

Validation: native local filter tests simulated the old clone3 EPERM profile, verified pthread creation failed before and succeeded after the wrapper, verified clone3 ENOSYS, and confirmed unshare and bpf remained denied with NO_NEW_PRIVS=1. The exact x86_64 binary also passed on two unrouted real-host candidates under the original Docker profile: clone3 ENOSYS=38, unshare/bpf EPERM=1, pthread creation succeeded, NO_NEW_PRIVS=1, and Guzzle outbound API requests returned HTTP 200. Local amd64 emulation cannot implement PR_SET_SECCOMP; do not treat an emulated startup failure as a production regression. Recheck application health after deployment; never use seccomp=unconfined as a workaround.
