#define _GNU_SOURCE
#include <errno.h>
#include <stddef.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/prctl.h>
#include <linux/audit.h>
#include <linux/filter.h>
#include <linux/seccomp.h>
#include <sys/syscall.h>
#if !defined(__x86_64__)
#error This wrapper requires Linux x86_64
#endif
#ifndef __NR_clone3
#define __NR_clone3 435
#endif
int main(int argc, char **argv) {
    if (argc < 2) { fprintf(stderr, "usage: clone3-fallback PROGRAM [ARGS...]\n"); return 64; }
    struct sock_filter filter[] = {
        BPF_STMT(BPF_LD | BPF_W | BPF_ABS, offsetof(struct seccomp_data, arch)),
        BPF_JUMP(BPF_JMP | BPF_JEQ | BPF_K, AUDIT_ARCH_X86_64, 1, 0),
        BPF_STMT(BPF_RET | BPF_K, SECCOMP_RET_KILL_PROCESS),
        BPF_STMT(BPF_LD | BPF_W | BPF_ABS, offsetof(struct seccomp_data, nr)),
        BPF_JUMP(BPF_JMP | BPF_JEQ | BPF_K, __NR_clone3, 0, 1),
        BPF_STMT(BPF_RET | BPF_K, SECCOMP_RET_ERRNO | ENOSYS),
        BPF_STMT(BPF_RET | BPF_K, SECCOMP_RET_ALLOW),
    };
    struct sock_fprog program = { .len = sizeof(filter) / sizeof(filter[0]), .filter = filter };
    if (prctl(PR_SET_NO_NEW_PRIVS, 1, 0, 0, 0) != 0) { perror("PR_SET_NO_NEW_PRIVS"); return 70; }
    if (prctl(PR_SET_SECCOMP, SECCOMP_MODE_FILTER, &program) != 0) { perror("PR_SET_SECCOMP"); return 70; }
    execvp(argv[1], &argv[1]);
    perror("execvp"); return 71;
}
