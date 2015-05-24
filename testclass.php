<!doctype html public "-//W3C//DTD HTML 4.0 //EN"> 
<html>
<head>
       <title>Title here!</title>
       
       <script language="javascript">
       
       function LinkedList()
{
    this.head = null;
    this.add = addlistnodetohead;
    this.remove = removelistnode;
}
function isempty()
{
    if (this.head == null)
       return true;
    else
       return false;
}

function addlistnodetohead(data)
{
    listnode = new ListNode(data);
    if (this.head == null)
    {
         this.head = listnode;
    }
    else
    {
        listnode.next = this.head;
        this.head = listnode;
    }
}

function removelistnode(data)
{
    if (this.head != null)
    {
        if (this.head.data == data)
        {
            var tmp = this.head;
            this.head = this.head.next;
            delete tmp;
        }
        else
        {
            var prev = this.head;
            var curr = this.head.next;
            while (curr != null)
            {
                if (curr.data == data)
                {
                     break;
                }
                else
                {
                    prev = curr;
                    curr = curr.next;
                }
            }
            if (curr != null)
            {
                prev.next = curr.next;
                delete curr;
            }
        }
    }
}


function ListNode(data)
{
    this.data = data;
    this.next = null;
}

cartimages = new LinkedList();

cartimages.add("praveen");
       </script>
</head>
<body>

</body>
</html>
