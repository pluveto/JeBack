from colorama import Fore, Back, Style, init


def prepare():
    init()  # 初始化控制台着色
    # print('\x1b[2J', end='', flush=True)  # 清屏


def info(msg, level=0, pre=''):
    print("    "*level + Fore.BLUE +
          "info: " + Fore.CYAN + pre + Style.RESET_ALL + msg)


def response(msg, level=0):
    print("    "*level + Fore.BLUE + "response: \n" +
          Style.RESET_ALL + offsetAllLine(msg, 2))


def offsetAllLine(lines, level=1):
    lines = '    '*level + lines.replace('\n', '\n' + '    '*level)
    return lines


def error(msg, level=0):
    print("    "*level + Fore.RED + "error: " + Style.RESET_ALL + msg)


def success(msg, level=0):
    print("    "*level + Fore.GREEN + "success: " + Style.RESET_ALL + msg)
