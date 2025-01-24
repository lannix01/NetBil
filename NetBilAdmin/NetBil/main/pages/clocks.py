import tkinter as tk
from time import strftime

# Create the main window
root = tk.Tk()
root.title("Digital Clock")

# Define a function to update the time
def time():
    string = strftime('%H:%M:%S %p')
    label.config(text=string)
    label.after(1000, time)

# Create a label to display the time
label = tk.Label(root, font=('calibri', 40, 'bold'), background='purple', foreground='white')
label.pack(anchor='center')

# Call the time function to update the label
time()

# Run the main loop
root.mainloop()
